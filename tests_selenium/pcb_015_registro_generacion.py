"""
PCB-015 / Mod-03 / HU-001
Prueba automatizada de registro de generaciones en SWCE.
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
RUTA_LOGIN = "/login"
RUTA_GENERACIONES = "/generaciones"


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


def esperar(driver, tiempo=12):
    """Crea una espera explícita."""
    return WebDriverWait(driver, tiempo)


def hacer_login(driver):
    """Realiza el inicio de sesión."""
    print("Abriendo login...")
    driver.get(f"{BASE_URL}{RUTA_LOGIN}")

    wait = esperar(driver)

    print("Capturando credenciales...")
    wait.until(EC.presence_of_element_located((By.NAME, "email"))).send_keys(EMAIL)
    driver.find_element(By.NAME, "password").send_keys(PASSWORD)

    print("Enviando formulario de acceso...")
    driver.find_element(
        By.XPATH,
        "//button[@type='submit' or contains(., 'Iniciar sesión') or contains(., 'Entrar') or contains(., 'Acceder')]"
    ).click()

    wait.until(EC.presence_of_element_located((By.TAG_NAME, "body")))
    time.sleep(2)
    print("Login realizado.")


def abrir_modulo_generaciones(driver):
    """Abre el módulo de generaciones."""
    print("Abriendo módulo de generaciones...")
    driver.get(f"{BASE_URL}{RUTA_GENERACIONES}")
    esperar(driver).until(EC.presence_of_element_located((By.TAG_NAME, "body")))
    time.sleep(2)


def imprimir_resultado(nombre_prueba, aprobado, detalle):
    """Imprime el resultado final."""
    print("\n" + "=" * 90)
    print(f"PRUEBA: {nombre_prueba}")
    print(f"RESULTADO: {'APROBADA' if aprobado else 'FALLIDA'}")
    print(f"DETALLE: {detalle}")
    print("=" * 90 + "\n")


# =========================================================
# FUNCIONES DE APOYO
# =========================================================
def limpiar_formulario(driver):
    """Recarga la página para limpiar el formulario."""
    print("Recargando página para limpiar formulario...")
    driver.refresh()
    esperar(driver).until(EC.presence_of_element_located((By.TAG_NAME, "body")))
    time.sleep(2)


def formulario_creacion_visible(driver):
    """Valida si el formulario de creación ya está visible."""
    xpaths = [
        "//input[contains(@wire:model, 'generacion')]",
        "//input[contains(@wire:model.live, 'generacion')]",
        "//input[contains(@placeholder, 'Ej. 2020-2023')]",
        "//label[contains(., 'Asigna la Generación')]",
    ]

    for xpath in xpaths:
        try:
            elementos = driver.find_elements(By.XPATH, xpath)
            for elemento in elementos:
                if elemento.is_displayed():
                    return True
        except Exception:
            continue

    return False


def abrir_panel_nueva_generacion(driver):
    """
    Abre el panel de creación solo si está cerrado.
    Evita volver a hacer click cuando ya está abierto.
    """
    print("Abriendo panel 'Nueva Generación'...")

    if formulario_creacion_visible(driver):
        print("El formulario de creación ya está visible. No se vuelve a abrir.")
        return

    xpaths = [
        "//*[contains(normalize-space(.), 'Nueva Generación')]/ancestor::button[1]",
        "//button[.//span[contains(normalize-space(.), 'Nueva Generación')]]",
        "//button[contains(., 'Nueva Generación')]",
    ]

    boton = None

    for xpath in xpaths:
        try:
            elementos = driver.find_elements(By.XPATH, xpath)
            for elemento in elementos:
                if elemento.is_displayed() and elemento.is_enabled():
                    boton = elemento
                    break
            if boton:
                break
        except Exception:
            continue

    if not boton:
        raise NoSuchElementException("No se encontró el botón 'Nueva Generación'.")

    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", boton)
    time.sleep(0.8)

    try:
        boton.click()
    except Exception:
        driver.execute_script("arguments[0].click();", boton)

    time.sleep(1.5)

    esperar(driver, 8).until(lambda d: formulario_creacion_visible(d))
    print("Panel de creación abierto correctamente.")


def obtener_input_generacion(driver):
    """Obtiene el input real de generación."""
    xpaths = [
        "//input[contains(@wire:model, 'generacion')]",
        "//input[contains(@wire:model.live, 'generacion')]",
        "//label[contains(., 'Asigna la Generación')]/following::input[1]",
        "//input[contains(@placeholder, 'Ej. 2020-2023')]",
        "//input[contains(@name, 'generacion') or contains(@id, 'generacion')]",
        "//input[@type='text']",
    ]

    for xpath in xpaths:
        try:
            elementos = driver.find_elements(By.XPATH, xpath)
            for elemento in elementos:
                if elemento.is_displayed() and elemento.is_enabled():
                    return elemento
        except Exception:
            continue

    raise NoSuchElementException("No se encontró el input de generación.")


def escribir_en_input(driver, elemento, texto):
    """Escribe en un input de forma robusta."""
    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", elemento)
    time.sleep(0.5)

    try:
        elemento.click()
    except Exception:
        driver.execute_script("arguments[0].click();", elemento)

    time.sleep(0.3)

    try:
        driver.execute_script("""
            arguments[0].value = '';
            arguments[0].dispatchEvent(new Event('input', { bubbles: true }));
            arguments[0].dispatchEvent(new Event('change', { bubbles: true }));
        """, elemento)
    except Exception:
        pass

    time.sleep(0.3)

    try:
        elemento.clear()
    except Exception:
        pass

    if str(texto) != "":
        try:
            elemento.send_keys(str(texto))
        except Exception:
            driver.execute_script("""
                arguments[0].value = arguments[1];
                arguments[0].dispatchEvent(new Event('input', { bubbles: true }));
                arguments[0].dispatchEvent(new Event('change', { bubbles: true }));
            """, elemento, str(texto))

    time.sleep(1)


def guardar_registro(driver):
    """Presiona el botón Guardar."""
    print("Guardando registro...")

    xpaths = [
        "//button[contains(., 'Guardar')]",
        "//button[@type='submit']",
    ]

    for xpath in xpaths:
        try:
            botones = driver.find_elements(By.XPATH, xpath)
            for boton in botones:
                if boton.is_displayed() and boton.is_enabled():
                    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", boton)
                    time.sleep(0.5)
                    try:
                        boton.click()
                    except Exception:
                        driver.execute_script("arguments[0].click();", boton)
                    time.sleep(2)
                    return
        except Exception:
            continue

    raise NoSuchElementException("No se encontró el botón Guardar.")


def obtener_buscador_generaciones(driver):
    """Obtiene el campo de búsqueda del módulo."""
    xpaths = [
        "//input[contains(@placeholder,'Buscar')]",
        "//input[contains(@wire:model, 'search')]",
        "//input[contains(@wire:model.live, 'search')]",
        "//input[contains(@name, 'search') or contains(@id, 'search')]",
    ]

    for xpath in xpaths:
        try:
            elementos = driver.find_elements(By.XPATH, xpath)
            for elemento in elementos:
                if elemento.is_displayed() and elemento.is_enabled():
                    return elemento
        except Exception:
            continue

    raise NoSuchElementException("No se encontró el buscador de generaciones.")


def buscar_generacion(driver, texto_busqueda):
    """Busca una generación en el listado."""
    print(f"Buscando generación: {texto_busqueda}")
    buscador = obtener_buscador_generaciones(driver)
    escribir_en_input(driver, buscador, texto_busqueda)
    time.sleep(2)


def detectar_mensaje_exito(driver):
    """Detecta mensaje de éxito."""
    try:
        html = driver.page_source.lower()
        return "generación creada correctamente" in html or "generacion creada correctamente" in html
    except Exception:
        return False


def detectar_mensaje_duplicado(driver):
    """Detecta mensaje de error por duplicidad."""
    try:
        html = driver.page_source.lower()
        return "ya existe en la base de datos" in html or "ya existe" in html
    except Exception:
        return False


def detectar_error_requerido(driver):
    """Detecta mensaje de error por campo vacío."""
    try:
        html = driver.page_source.lower()
        return "el campo generación es obligatorio" in html or "el campo generacion es obligatorio" in html
    except Exception:
        return False


def existe_generacion_en_tabla(driver, texto):
    """Valida si una generación aparece en la tabla."""
    try:
        driver.find_element(By.XPATH, f"//*[contains(text(), '{texto}')]")
        return True
    except Exception:
        return False


# =========================================================
# CASOS DE PRUEBA
# =========================================================
def registrar_generacion_correctamente(driver):
    """Caso 1: registrar una generación correcta."""
    print("\nCaso 1: registro correcto")

    abrir_panel_nueva_generacion(driver)

    input_generacion = obtener_input_generacion(driver)
    escribir_en_input(driver, input_generacion, "2030-2033")

    guardar_registro(driver)

    if detectar_mensaje_exito(driver):
        print("OK: la generación se registró correctamente.")
        return True

    print("REVISAR: no se detectó claramente el mensaje de éxito.")
    return False


def registrar_generacion_duplicada(driver):
    """Caso 2: intentar registrar una generación duplicada."""
    print("\nCaso 2: validación de duplicidad")

    limpiar_formulario(driver)
    abrir_panel_nueva_generacion(driver)

    input_generacion = obtener_input_generacion(driver)
    escribir_en_input(driver, input_generacion, "2030-2033")

    guardar_registro(driver)

    if detectar_mensaje_duplicado(driver):
        print("OK: se detectó el error de duplicidad.")
        return True

    print("REVISAR: no se detectó claramente el mensaje de duplicidad.")
    return False


def registrar_generacion_vacia(driver):
    """Caso 3: intentar registrar sin capturar la generación."""
    print("\nCaso 3: validación de campo obligatorio")

    limpiar_formulario(driver)
    abrir_panel_nueva_generacion(driver)

    input_generacion = obtener_input_generacion(driver)
    escribir_en_input(driver, input_generacion, "")

    guardar_registro(driver)

    if detectar_error_requerido(driver):
        print("OK: se detectó el error de campo obligatorio.")
        return True

    print("REVISAR: no se detectó claramente el mensaje de validación.")
    return False


def validar_registro_en_listado(driver):
    """Caso 4: validar que la generación creada aparece en el listado."""
    print("\nCaso 4: validación en listado")

    abrir_modulo_generaciones(driver)
    buscar_generacion(driver, "2030-2033")

    if existe_generacion_en_tabla(driver, "2030-2033"):
        print("OK: la generación aparece en el listado.")
        return True

    print("REVISAR: la generación no apareció en el listado.")
    return False


# =========================================================
# PRUEBA PRINCIPAL
# =========================================================
def prueba_pcb_015_registro_generacion():
    """Ejecuta la prueba principal de registro de generación."""
    driver = iniciar_navegador()

    try:
        print("Iniciando prueba PCB-015...")
        hacer_login(driver)
        abrir_modulo_generaciones(driver)

        resultados = []
        resultados.append(registrar_generacion_correctamente(driver))
        resultados.append(registrar_generacion_duplicada(driver))
        resultados.append(registrar_generacion_vacia(driver))
        resultados.append(validar_registro_en_listado(driver))

        if all(resultados):
            imprimir_resultado(
                "PCB-015 - Registro de generación",
                True,
                "Los casos ejecutados respondieron conforme a lo esperado."
            )
        else:
            imprimir_resultado(
                "PCB-015 - Registro de generación",
                False,
                "Uno o más casos requieren revisión manual."
            )

    except TimeoutException as e:
        imprimir_resultado(
            "PCB-015 - Registro de generación",
            False,
            f"Tiempo de espera agotado: {str(e)}"
        )

    except NoSuchElementException as e:
        imprimir_resultado(
            "PCB-015 - Registro de generación",
            False,
            f"No se encontró un elemento esperado: {str(e)}"
        )

    except Exception as e:
        imprimir_resultado(
            "PCB-015 - Registro de generación",
            False,
            f"Ocurrió una excepción: {str(e)}"
        )

    finally:
        driver.quit()
        print("Navegador cerrado.")


if __name__ == "__main__":
    prueba_pcb_015_registro_generacion()
