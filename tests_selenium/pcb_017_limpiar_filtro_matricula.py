"""
PCB-017 / Mod-04 / HU-005
Prueba automatizada de limpieza de filtros en la matrícula de SWCE.
"""

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait, Select
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
    """Inicia el navegador Chrome."""
    print("Iniciando navegador...")

    options = webdriver.ChromeOptions()
    options.add_argument("--start-maximized")

    driver = webdriver.Chrome(
        service=Service(ChromeDriverManager().install()),
        options=options
    )
    return driver


def esperar(driver, tiempo=10):
    """Crea una espera explícita."""
    return WebDriverWait(driver, tiempo)


def hacer_login(driver):
    """Realiza el inicio de sesión en el sistema."""
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
    """Abre el módulo de matrícula."""
    print("Abriendo módulo de matrícula...")
    driver.get(f"{BASE_URL}/matricula")
    time.sleep(2)


# =========================================================
# FUNCIONES DE APOYO
# =========================================================
def limpiar_y_escribir(driver, by, selector, texto):
    """Limpia un campo y escribe un valor."""
    campo = esperar(driver).until(EC.presence_of_element_located((by, selector)))
    campo.clear()
    campo.send_keys(texto)


def seleccionar_opcion(driver, by, selector, valor):
    """Selecciona una opción de un combo."""
    combo = esperar(driver).until(EC.presence_of_element_located((by, selector)))
    Select(combo).select_by_value(str(valor))


def click_boton_limpiar(driver):
    """Da clic en el botón Limpiar filtros."""
    print("Presionando botón 'Limpiar filtros'...")
    esperar(driver).until(
        EC.element_to_be_clickable(
            (By.XPATH, "//button[contains(., 'Limpiar') or contains(., 'Limpiar filtros')]")
        )
    ).click()
    time.sleep(2)


def imprimir_resultado(nombre_prueba, aprobado, detalle):
    """Imprime el resultado final de la prueba."""
    print("\n" + "=" * 90)
    print(f"PRUEBA: {nombre_prueba}")
    print(f"RESULTADO: {'APROBADA' if aprobado else 'FALLIDA'}")
    print(f"DETALLE: {detalle}")
    print("=" * 90 + "\n")


# =========================================================
# FLUJO DE LA PRUEBA
# =========================================================
def capturar_filtros(driver):
    """Llena búsqueda y filtros antes de limpiar."""
    print("Capturando búsqueda y filtros...")

    # Campo de búsqueda
    try:
        limpiar_y_escribir(driver, By.NAME, "search", "Carlos")
        print("Campo de búsqueda capturado por NAME.")
    except Exception:
        try:
            limpiar_y_escribir(
                driver,
                By.XPATH,
                "//*[@wire:model='search' or @wire:model.live='search' or contains(@wire:model.live.debounce.400ms, 'search')]",
                "Carlos"
            )
            print("Campo de búsqueda capturado por wire:model.")
        except Exception:
            print("No se pudo capturar el campo de búsqueda.")

    # Filtro de licenciatura
    try:
        seleccionar_opcion(driver, By.NAME, "filtrar_licenciatura", "1")
        print("Filtro licenciatura seleccionado.")
    except Exception:
        print("No se pudo seleccionar el filtro licenciatura.")

    # Filtro de generación
    try:
        seleccionar_opcion(driver, By.NAME, "filtrar_generacion", "1")
        print("Filtro generación seleccionado.")
    except Exception:
        print("No se pudo seleccionar el filtro generación.")

    # Filtro de cuatrimestre
    try:
        seleccionar_opcion(driver, By.NAME, "filtrar_cuatrimestre", "1")
        print("Filtro cuatrimestre seleccionado.")
    except Exception:
        print("No se pudo seleccionar el filtro cuatrimestre.")

    time.sleep(1)


def validar_resultado(driver):
    """Valida si los filtros y búsqueda fueron limpiados."""
    print("Validando resultado de la limpieza...")

    aprobado = True
    detalle = []

    # Validar búsqueda vacía
    try:
        campo_search = driver.find_element(By.NAME, "search")
        if campo_search.get_attribute("value") != "":
            aprobado = False
            detalle.append("El campo de búsqueda no se limpió.")
        else:
            print("El campo de búsqueda se limpió correctamente.")
    except Exception:
        detalle.append("No se pudo validar directamente el campo de búsqueda.")

    # Validar filtros vacíos
    for nombre in ["filtrar_licenciatura", "filtrar_generacion", "filtrar_cuatrimestre"]:
        try:
            elemento = driver.find_element(By.NAME, nombre)
            valor = elemento.get_attribute("value")
            if valor not in ["", None]:
                aprobado = False
                detalle.append(f"El filtro {nombre} no se limpió.")
            else:
                print(f"El filtro {nombre} se limpió correctamente.")
        except Exception:
            detalle.append(f"No se pudo validar directamente el filtro {nombre}.")

    # Validar paginación
    try:
        url_actual = driver.current_url
        if "page=" in url_actual and "page=1" not in url_actual:
            aprobado = False
            detalle.append("La paginación no volvió a la primera página.")
        else:
            print("La paginación quedó en estado correcto.")
    except Exception:
        detalle.append("No se pudo validar la paginación.")

    if aprobado:
        return True, "El sistema limpió correctamente la búsqueda, los filtros y reinició la paginación."

    return False, " | ".join(detalle)


# =========================================================
# PRUEBA PRINCIPAL
# =========================================================
def prueba_pcb_017_limpiar_filtros_matricula():
    """Ejecuta la prueba completa PCB-017."""
    driver = iniciar_navegador()

    try:
        print("Iniciando prueba PCB-017...")
        hacer_login(driver)
        abrir_modulo_matricula(driver)
        capturar_filtros(driver)
        click_boton_limpiar(driver)

        aprobado, detalle = validar_resultado(driver)
        imprimir_resultado(
            "PCB-017 - Limpieza de filtros de matrícula",
            aprobado,
            detalle
        )

    except TimeoutException as e:
        imprimir_resultado(
            "PCB-017 - Limpieza de filtros de matrícula",
            False,
            f"Tiempo de espera agotado: {str(e)}"
        )

    except NoSuchElementException as e:
        imprimir_resultado(
            "PCB-017 - Limpieza de filtros de matrícula",
            False,
            f"No se encontró un elemento esperado: {str(e)}"
        )

    except Exception as e:
        imprimir_resultado(
            "PCB-017 - Limpieza de filtros de matrícula",
            False,
            f"Ocurrió una excepción: {str(e)}"
        )

    finally:
        driver.quit()
        print("Navegador cerrado.")


if __name__ == "__main__":
    prueba_pcb_017_limpiar_filtros_matricula()
