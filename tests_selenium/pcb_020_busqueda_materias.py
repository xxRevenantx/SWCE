"""
PCB-020 / Mod-06 / HU-004
Prueba automatizada de búsqueda de materia en SWCE.
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
RUTA_MATERIAS = "/materias"


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
    wait.until(EC.presence_of_element_located((By.NAME, "email"))).send_keys(EMAIL)
    driver.find_element(By.NAME, "password").send_keys(PASSWORD)

    driver.find_element(
        By.XPATH,
        "//button[contains(., 'Iniciar sesión') or contains(., 'Entrar') or contains(., 'Acceder')]"
    ).click()

    time.sleep(2)
    print("Login realizado.")


def abrir_modulo_materias(driver):
    print("Abriendo módulo de materias...")
    driver.get(f"{BASE_URL}{RUTA_MATERIAS}")
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
def obtener_input_busqueda(driver):
    xpaths = [
        "//input[contains(@placeholder, 'Buscar por materia o clave')]",
        "//input[contains(@wire:model.live, 'search')]",
        "//input[contains(@wire:model, 'search')]",
        "//input[contains(@name, 'search') or contains(@id, 'search')]",
    ]

    for xpath in xpaths:
        try:
            return esperar(driver, 4).until(
                EC.presence_of_element_located((By.XPATH, xpath))
            )
        except Exception:
            continue

    raise NoSuchElementException("No se encontró el campo de búsqueda.")


def escribir_busqueda(driver, texto):
    print(f"Escribiendo búsqueda: {texto}")
    campo = obtener_input_busqueda(driver)
    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", campo)
    time.sleep(1)
    campo.clear()
    campo.send_keys(texto)
    time.sleep(2)


def contar_filas_materias(driver):
    try:
        filas = driver.find_elements(
            By.XPATH,
            "//table//tbody/tr[not(contains(., 'No hay registros'))]"
        )
        return len(filas)
    except Exception:
        return 0


def existe_texto_en_tabla(driver, texto):
    try:
        driver.find_element(
            By.XPATH,
            f"//table//tbody//*[contains(translate(., 'abcdefghijklmnopqrstuvwxyzáéíóú', 'ABCDEFGHIJKLMNOPQRSTUVWXYZÁÉÍÓÚ'), '{texto.upper()}')]"
        )
        return True
    except Exception:
        return False


# =========================================================
# FLUJO DE LA PRUEBA
# =========================================================
def ejecutar_busqueda(driver, texto):
    print("Contando filas antes de buscar...")
    filas_antes = contar_filas_materias(driver)
    print(f"Filas antes: {filas_antes}")

    escribir_busqueda(driver, texto)

    print("Contando filas después de buscar...")
    filas_despues = contar_filas_materias(driver)
    print(f"Filas después: {filas_despues}")

    return filas_antes, filas_despues


def validar_resultado(driver, texto_buscado, filas_antes, filas_despues):
    detalle = []

    if filas_antes == 0:
        return False, "No había materias visibles antes de ejecutar la búsqueda."

    if filas_despues == 0:
        detalle.append("La búsqueda no devolvió filas visibles; revisar si los datos de prueba coinciden.")

    if filas_despues > 0 and not existe_texto_en_tabla(driver, texto_buscado):
        detalle.append("No se detectó visualmente el texto buscado en la tabla.")

    if detalle:
        return False, " | ".join(detalle)

    return True, "El sistema aplicó correctamente la búsqueda de materia y mostró resultados coincidentes."


# =========================================================
# PRUEBA PRINCIPAL
# =========================================================
def prueba_pcb_020_busqueda_materia():
    driver = iniciar_navegador()

    try:
        print("Iniciando prueba PCB-020...")
        hacer_login(driver)
        abrir_modulo_materias(driver)

        texto_buscado = "MAT"
        filas_antes, filas_despues = ejecutar_busqueda(driver, texto_buscado)

        aprobado, detalle = validar_resultado(driver, texto_buscado, filas_antes, filas_despues)
        imprimir_resultado(
            "PCB-020 - Búsqueda de materia",
            aprobado,
            detalle
        )

    except TimeoutException as e:
        imprimir_resultado(
            "PCB-020 - Búsqueda de materia",
            False,
            f"Tiempo de espera agotado: {str(e)}"
        )

    except NoSuchElementException as e:
        imprimir_resultado(
            "PCB-020 - Búsqueda de materia",
            False,
            f"No se encontró un elemento esperado: {str(e)}"
        )

    except Exception as e:
        imprimir_resultado(
            "PCB-020 - Búsqueda de materia",
            False,
            f"Ocurrió una excepción: {str(e)}"
        )

    finally:
        driver.quit()
        print("Navegador cerrado.")


if __name__ == "__main__":
    prueba_pcb_020_busqueda_materia()
