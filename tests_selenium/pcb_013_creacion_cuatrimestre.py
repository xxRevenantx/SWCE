"""
PCB-013 / Mod-02 / HU-001
Prueba automatizada de creación de cuatrimestre en SWCE.
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
RUTA_CUATRIMESTRES = "/cuatrimestres"


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


def abrir_modulo_cuatrimestres(driver):
    print("Abriendo módulo de cuatrimestres...")
    driver.get(f"{BASE_URL}{RUTA_CUATRIMESTRES}")
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
def obtener_texto_pagina(driver):
    try:
        return driver.page_source
    except Exception:
        return ""


def limpiar_formulario(driver):
    print("Recargando página para limpiar formulario...")
    driver.refresh()
    time.sleep(3)
    abrir_collapse_nuevo_cuatrimestre(driver)


def abrir_collapse_nuevo_cuatrimestre(driver):
    print("Abriendo collapse 'Nuevo cuatrimestre'...")

    span_texto = esperar(driver, 8).until(
        EC.presence_of_element_located(
            (By.XPATH, "//*[contains(text(), 'Nuevo cuatrimestre')]")
        )
    )

    boton = span_texto.find_element(By.XPATH, "./ancestor::button[1]")
    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", boton)
    time.sleep(1)
    driver.execute_script("arguments[0].click();", boton)
    time.sleep(2)

    esperar(driver, 8).until(
        EC.presence_of_element_located(
            (By.XPATH, "//label[contains(., 'No. de Cuatrimestre')]")
        )
    )

    print("Collapse abierto correctamente.")
    """
    Abre el collapse 'Nuevo cuatrimestre' antes de capturar los campos.
    """
    print("Abriendo collapse 'Nuevo cuatrimestre'...")

    xpaths_boton = [
        "//button[.//span[contains(., 'Nuevo cuatrimestre')]]",
        "//button[contains(., 'Nuevo cuatrimestre')]",
        "//*[self::button or @role='button'][.//*[contains(., 'Nuevo cuatrimestre')] or contains(., 'Nuevo cuatrimestre')]",
    ]

    boton = None

    for xpath in xpaths_boton:
        try:
            elementos = driver.find_elements(By.XPATH, xpath)
            for elemento in elementos:
                if elemento.is_displayed():
                    boton = elemento
                    break
            if boton:
                break
        except Exception:
            continue

    if not boton:
        raise Exception("No se encontró el botón 'Nuevo cuatrimestre'.")

    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", boton)
    time.sleep(1)

    try:
        boton.click()
    except Exception:
        driver.execute_script("arguments[0].click();", boton)

    time.sleep(1.5)

    # Si no abrió, volver a intentar con JS
    labels = driver.find_elements(By.XPATH, "//label[contains(., 'No. de Cuatrimestre')]")
    if not labels:
        print("Primer intento no abrió el collapse, reintentando con JavaScript...")
        driver.execute_script("arguments[0].click();", boton)
        time.sleep(1.5)

    esperar(driver, 8).until(
        EC.presence_of_element_located(
            (By.XPATH, "//label[contains(., 'No. de Cuatrimestre')]")
        )
    )

    print("Collapse abierto correctamente.")

def obtener_input_no_cuatrimestre(driver):
    xpaths = [
        "//label[contains(., 'No. de Cuatrimestre')]/following::input[1]",
        "//input[contains(@placeholder, 'No. de cuatrimestre')]",
        "//input[contains(@wire:model, 'no_cuatrimestre')]",
        "//input[contains(@wire:model.live, 'no_cuatrimestre')]",
        "//input[contains(@name, 'no_cuatrimestre') or contains(@id, 'no_cuatrimestre')]",
        "//input[@type='number']",
    ]

    for xpath in xpaths:
        try:
            return esperar(driver, 3).until(
                EC.presence_of_element_located((By.XPATH, xpath))
            )
        except Exception:
            continue

    raise NoSuchElementException("No se encontró el input de No. de Cuatrimestre.")


def obtener_input_nombre_cuatrimestre(driver):
    xpaths = [
        "//label[contains(., 'Nombre Cuatrimestre')]/following::input[1]",
        "//label[contains(., 'Nombre del cuatrimestre')]/following::input[1]",
        "//input[contains(@placeholder, 'Ej. Primer cuatrimestre')]",
        "//input[contains(@wire:model, 'nombre_cuatrimestre')]",
        "//input[contains(@wire:model.live, 'nombre_cuatrimestre')]",
        "//input[contains(@name, 'nombre_cuatrimestre') or contains(@id, 'nombre_cuatrimestre')]",
    ]

    for xpath in xpaths:
        try:
            return esperar(driver, 3).until(
                EC.presence_of_element_located((By.XPATH, xpath))
            )
        except Exception:
            continue

    raise NoSuchElementException("No se encontró el input de Nombre Cuatrimestre.")


def obtener_select_mes(driver):
    """
    Obtiene el select real de meses dentro del formulario expandido.
    """
    xpaths = [
        "//label[contains(., 'Selecciona los meses')]/following::select[1]",
        "//label[contains(., 'Selecciona los meses')]/following::*//select[1]",
        "//select[preceding::label[contains(., 'Selecciona los meses')]][1]",
        "//select[contains(@wire:model, 'mes_id')]",
        "//select[contains(@wire:model.live, 'mes_id')]",
        "(//select)[1]",
    ]

    for xpath in xpaths:
        elementos = driver.find_elements(By.XPATH, xpath)
        for elemento in elementos:
            try:
                if elemento.is_displayed() and elemento.is_enabled():
                    return elemento
            except Exception:
                pass

    raise NoSuchElementException("No se encontró el select visible de meses.")


def escribir_input(driver, elemento, texto):
    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", elemento)
    time.sleep(0.5)
    elemento.clear()
    time.sleep(0.3)
    elemento.send_keys(texto)
    time.sleep(0.8)


def seleccionar_mes_por_indice(driver, indice_opcion):
    """
    Selecciona el mes por índice real del select.
    Debe ir del 1 al 3, porque 0 normalmente es la opción por defecto.
    """
    print(f"Seleccionando mes por índice: {indice_opcion}")

    select = obtener_select_mes(driver)
    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", select)
    time.sleep(0.5)

    if indice_opcion not in [1, 2, 3]:
        raise Exception("El índice del mes debe estar entre 1 y 3.")

    # Intento 1: con Select()
    try:
        combo = Select(select)
        opciones_validas = combo.options

        if len(opciones_validas) <= indice_opcion:
            raise Exception(f"El select no tiene la opción con índice {indice_opcion}")

        combo.select_by_index(indice_opcion)
        time.sleep(1)

        texto = opciones_validas[indice_opcion].text.strip()
        valor = opciones_validas[indice_opcion].get_attribute("value")
        print(f"Mes seleccionado con Select(): índice {indice_opcion} -> {texto} ({valor})")
        return
    except Exception:
        print("No se pudo seleccionar con Select(), se intentará con JavaScript...")

    # Intento 2: con JavaScript
    resultado = driver.execute_script("""
        const el = arguments[0];
        const indice = arguments[1];

        if (!el || !el.options) return null;
        if (indice < 0 || indice >= el.options.length) return null;

        const opt = el.options[indice];
        el.value = opt.value;
        el.dispatchEvent(new Event('input', { bubbles: true }));
        el.dispatchEvent(new Event('change', { bubbles: true }));

        return {
            value: opt.value,
            text: opt.text
        };
    """, select, indice_opcion)

    if not resultado or resultado["value"] in [None, "", "0"]:
        raise Exception(f"No se pudo seleccionar el mes en el índice {indice_opcion}")

    time.sleep(1)
    print(f"Mes seleccionado con JavaScript: índice {indice_opcion} -> {resultado['text']} ({resultado['value']})")


def guardar(driver):
    print("Presionando botón Guardar...")

    xpaths = [
        "//button[contains(., 'Guardar')]",
        "//button[@type='submit']",
    ]

    for xpath in xpaths:
        try:
            boton = esperar(driver, 4).until(
                EC.element_to_be_clickable((By.XPATH, xpath))
            )
            driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", boton)
            time.sleep(0.5)
            driver.execute_script("arguments[0].click();", boton)
            time.sleep(2)
            return
        except Exception:
            continue

    raise NoSuchElementException("No se encontró el botón Guardar.")


# =========================================================
# CASOS DE PRUEBA
# =========================================================
def caso_registro_correcto(driver):
    print("\nCaso 1: registro correcto")

    escribir_input(driver, obtener_input_no_cuatrimestre(driver), "8")
    escribir_input(driver, obtener_input_nombre_cuatrimestre(driver), "Octavo")
    seleccionar_mes_por_indice(driver, 1)
    guardar(driver)

    texto = obtener_texto_pagina(driver)

    if "¡Cuatrimestre creado correctamente!" in texto or "Cuatrimestre creado correctamente" in texto:
        print("OK: se creó correctamente el cuatrimestre.")
        return True
    else:
        print("REVISAR: no se detectó claramente el mensaje de éxito.")
        return False


def caso_cuatrimestre_duplicado(driver):
    print("\nCaso 2: cuatrimestre duplicado")

    escribir_input(driver, obtener_input_no_cuatrimestre(driver), "1")
    escribir_input(driver, obtener_input_nombre_cuatrimestre(driver), "Primero")
    seleccionar_mes_por_indice(driver, 1)
    guardar(driver)

    texto = obtener_texto_pagina(driver)

    if "¡El cuatrimestre ya existe!" in texto or "cuatrimestre ya existe" in texto.lower():
        print("OK: se detectó duplicidad.")
        return True
    else:
        print("REVISAR: no se detectó claramente la duplicidad.")
        return False


def caso_nombre_vacio(driver):
    print("\nCaso 3: nombre vacío")

    escribir_input(driver, obtener_input_no_cuatrimestre(driver), "5")

    input_nombre = obtener_input_nombre_cuatrimestre(driver)
    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", input_nombre)
    input_nombre.clear()
    time.sleep(0.5)

    seleccionar_mes_por_indice(driver, 2)
    guardar(driver)

    texto = obtener_texto_pagina(driver)

    if "El campo nombre del cuatrimestre es obligatorio." in texto:
        print("OK: se detectó validación del nombre.")
        return True
    else:
        print("REVISAR: no se detectó claramente la validación del nombre.")
        return False


def caso_mes_invalido(driver):
    print("\nCaso 4: mes no seleccionado o inválido")

    escribir_input(driver, obtener_input_no_cuatrimestre(driver), "5")
    escribir_input(driver, obtener_input_nombre_cuatrimestre(driver), "Quinto")

    select = obtener_select_mes(driver)

    try:
        Select(select).select_by_index(0)
    except Exception:
        driver.execute_script("""
            const el = arguments[0];
            if (el && el.options && el.options.length > 0) {
                el.selectedIndex = 0;
                el.dispatchEvent(new Event('input', { bubbles: true }));
                el.dispatchEvent(new Event('change', { bubbles: true }));
            }
        """, select)

    time.sleep(1)
    guardar(driver)

    texto = obtener_texto_pagina(driver)

    if "El campo mes es obligatorio." in texto or "El mes seleccionado no es válido." in texto:
        print("OK: se detectó error de validación del mes.")
        return True
    else:
        print("REVISAR: no se detectó claramente la validación del mes.")
        return False


# =========================================================
# PRUEBA PRINCIPAL
# =========================================================
def prueba_pcb_013_creacion_cuatrimestre():
    driver = iniciar_navegador()

    try:
        print("Iniciando prueba PCB-013...")
        hacer_login(driver)
        abrir_modulo_cuatrimestres(driver)

        resultados = []

        abrir_collapse_nuevo_cuatrimestre(driver)
        resultados.append(caso_registro_correcto(driver))

        limpiar_formulario(driver)
        resultados.append(caso_cuatrimestre_duplicado(driver))

        limpiar_formulario(driver)
        resultados.append(caso_nombre_vacio(driver))

        limpiar_formulario(driver)
        resultados.append(caso_mes_invalido(driver))

        if all(resultados):
            imprimir_resultado(
                "PCB-013 - Creación de cuatrimestre",
                True,
                "Los casos ejecutados respondieron conforme a lo esperado, abriendo primero el collapse y seleccionando correctamente el mes."
            )
        else:
            imprimir_resultado(
                "PCB-013 - Creación de cuatrimestre",
                False,
                "Uno o más casos requieren revisión manual."
            )

    except TimeoutException as e:
        imprimir_resultado(
            "PCB-013 - Creación de cuatrimestre",
            False,
            f"Tiempo de espera agotado: {str(e)}"
        )

    except NoSuchElementException as e:
        imprimir_resultado(
            "PCB-013 - Creación de cuatrimestre",
            False,
            f"No se encontró un elemento esperado: {str(e)}"
        )

    except Exception as e:
        imprimir_resultado(
            "PCB-013 - Creación de cuatrimestre",
            False,
            f"Ocurrió una excepción: {str(e)}"
        )

    finally:
        driver.quit()
        print("Navegador cerrado.")


if __name__ == "__main__":
    prueba_pcb_013_creacion_cuatrimestre()
