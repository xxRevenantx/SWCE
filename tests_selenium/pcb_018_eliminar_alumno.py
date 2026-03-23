"""
PCB-018 / Mod-04 / HU-005
Prueba automatizada de eliminación de alumno en el módulo de matrícula de SWCE.
"""

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, NoSuchElementException
from webdriver_manager.chrome import ChromeDriverManager
import time


# =========================================================
# CONFIGURACIÓN GENERAL
# =========================================================
BASE_URL = "http://swce.test"
EMAIL = "admin@swce.com"
PASSWORD = "Swce#2026"


# =========================================================
# FUNCIONES GENERALES
# =========================================================
def iniciar_navegador():
    print("Iniciando navegador...")

    options = webdriver.ChromeOptions()
    options.add_argument("--start-maximized")

    driver = webdriver.Chrome(
        service=Service(ChromeDriverManager().install()),
        options=options
    )
    return driver


def esperar(driver, tiempo=12):
    return WebDriverWait(driver, tiempo)


def hacer_login(driver):
    print("Abriendo login...")
    driver.get(f"{BASE_URL}/login")

    wait = esperar(driver)

    print("Capturando credenciales...")
    wait.until(EC.presence_of_element_located((By.NAME, "email"))).send_keys(EMAIL)
    driver.find_element(By.NAME, "password").send_keys(PASSWORD)

    print("Enviando formulario de acceso...")
    driver.find_element(
        By.XPATH,
        "//button[contains(., 'Iniciar sesión') or contains(., 'Entrar') or contains(., 'Acceder')]"
    ).click()

    time.sleep(2)
    print("Login realizado.")


def abrir_modulo_matricula(driver):
    print("Abriendo módulo de matrícula...")
    driver.get(f"{BASE_URL}/matricula")
    time.sleep(2)


def imprimir_resultado(nombre_prueba, aprobado, detalle):
    print("\n" + "=" * 90)
    print(f"PRUEBA: {nombre_prueba}")
    print(f"RESULTADO: {'APROBADA' if aprobado else 'FALLIDA'}")
    print(f"DETALLE: {detalle}")
    print("=" * 90 + "\n")


# =========================================================
# FUNCIONES DE APOYO
# =========================================================
def obtener_total_filas(driver):
    """Obtiene las filas visibles de la tabla."""
    try:
        filas = driver.find_elements(
            By.XPATH,
            "//table//tbody/tr[contains(@class, 'hover:bg-neutral-50')]"
        )
        return len(filas)
    except Exception:
        return 0


def obtener_primer_boton_eliminar(driver):
    """
    Busca el primer botón eliminar real del Blade.
    Aquí el botón usa @click='destroyAlumno(id)'.
    """
    xpaths = [
        "(//button[contains(@click, 'destroyAlumno')])[1]",
        "(//button[contains(@x-on:click, 'destroyAlumno')])[1]",
        "(//button[contains(@class, 'bg-rose-600')])[1]",
        "(//button[.//*[contains(@class, 'trash-2')]])[1]",
    ]

    for xpath in xpaths:
        try:
            boton = esperar(driver, 4).until(
                EC.presence_of_element_located((By.XPATH, xpath))
            )
            return boton
        except Exception:
            continue

    raise NoSuchElementException("No se encontró un botón de eliminación con el patrón destroyAlumno.")


def confirmar_eliminacion(driver):
    """Confirma la eliminación en SweetAlert."""
    print("Confirmando eliminación en SweetAlert...")

    boton_confirmar = esperar(driver).until(
        EC.element_to_be_clickable(
            (By.XPATH, "//button[contains(., 'Sí, eliminar') or contains(., 'Si, eliminar')]")
        )
    )
    boton_confirmar.click()
    time.sleep(2)


def detectar_mensaje_exito(driver):
    """
    Intenta detectar evidencia visual de éxito.
    En Livewire/SweetAlert a veces el texto no queda fijo mucho tiempo,
    por eso además validamos por disminución de filas.
    """
    try:
        html = driver.page_source.lower()
        if "alumno eliminado" in html:
            return True
    except Exception:
        pass

    try:
        # SweetAlert suele pintar el título en el DOM
        driver.find_element(By.XPATH, "//*[contains(text(), 'Alumno Eliminado')]")
        return True
    except Exception:
        return False


# =========================================================
# FLUJO DE LA PRUEBA
# =========================================================
def ejecutar_eliminacion(driver):
    print("Contando filas antes de eliminar...")
    filas_antes = obtener_total_filas(driver)
    print(f"Filas antes: {filas_antes}")

    if filas_antes == 0:
        raise Exception("No hay registros visibles para ejecutar la prueba.")

    print("Buscando botón eliminar...")
    boton_eliminar = obtener_primer_boton_eliminar(driver)

    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", boton_eliminar)
    time.sleep(1)

    print("Presionando botón eliminar...")
    driver.execute_script("arguments[0].click();", boton_eliminar)
    time.sleep(1)

    confirmar_eliminacion(driver)

    print("Esperando actualización de la tabla...")
    time.sleep(3)

    print("Contando filas después de eliminar...")
    filas_despues = obtener_total_filas(driver)
    print(f"Filas después: {filas_despues}")

    return filas_antes, filas_despues


def validar_resultado(driver, filas_antes, filas_despues):
    print("Validando resultado de la eliminación...")

    aprobado = True
    detalle = []

    if filas_despues >= filas_antes:
        aprobado = False
        detalle.append("La cantidad de filas no disminuyó después de eliminar.")
    else:
        print("La cantidad de filas disminuyó correctamente.")

    if detectar_mensaje_exito(driver):
        print("Se detectó mensaje de éxito.")
    else:
        detalle.append("No se detectó claramente el mensaje de éxito 'Alumno Eliminado'.")

    print(f"URL actual: {driver.current_url}")

    if aprobado:
        return True, "El sistema eliminó correctamente al alumno y actualizó la tabla."

    return False, " | ".join(detalle)


# =========================================================
# PRUEBA PRINCIPAL
# =========================================================
def prueba_pcb_018_eliminacion_alumno():
    driver = iniciar_navegador()

    try:
        print("Iniciando prueba PCB-018...")
        hacer_login(driver)
        abrir_modulo_matricula(driver)

        filas_antes, filas_despues = ejecutar_eliminacion(driver)

        aprobado, detalle = validar_resultado(driver, filas_antes, filas_despues)
        imprimir_resultado(
            "PCB-018 - Eliminación de alumno",
            aprobado,
            detalle
        )

    except TimeoutException as e:
        imprimir_resultado(
            "PCB-018 - Eliminación de alumno",
            False,
            f"Tiempo de espera agotado: {str(e)}"
        )

    except NoSuchElementException as e:
        imprimir_resultado(
            "PCB-018 - Eliminación de alumno",
            False,
            f"No se encontró un elemento esperado: {str(e)}"
        )

    except Exception as e:
        imprimir_resultado(
            "PCB-018 - Eliminación de alumno",
            False,
            f"Ocurrió una excepción: {str(e)}"
        )

    finally:
        driver.quit()
        print("Navegador cerrado.")


if __name__ == "__main__":
    prueba_pcb_018_eliminacion_alumno()
