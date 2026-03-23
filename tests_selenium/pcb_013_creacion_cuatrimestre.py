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


def formulario_visible(driver):
    try:
        elementos = driver.find_elements(
            By.XPATH,
            "//input[contains(@placeholder, 'No. de cuatrimestre')] | "
            "//label[contains(., 'No. de Cuatrimestre')]"
        )
        for elemento in elementos:
            if elemento.is_displayed():
                return True
        return False
    except Exception:
        return False


def limpiar_formulario(driver):
    print("Recargando página para limpiar formulario...")
    driver.refresh()
    time.sleep(3)


def abrir_panel_nuevo_cuatrimestre(driver):
    """
    Abre el panel o collapse de alta de cuatrimestre.
    """
    print("Abriendo panel 'Nuevo cuatrimestre'...")

    if formulario_visible(driver):
        print("El formulario ya está visible.")
        return

    xpaths_texto = [
        "//*[contains(normalize-space(.), 'Nuevo cuatrimestre')]",
        "//span[contains(normalize-space(.), 'Nuevo cuatrimestre')]",
    ]

    boton = None

    for xpath in xpaths_texto:
        try:
            elementos = driver.find_elements(By.XPATH, xpath)
            for elemento in elementos:
                if not elemento.is_displayed():
                    continue

                try:
                    candidato = elemento.find_element(By.XPATH, "./ancestor::button[1]")
                    if candidato.is_displayed():
                        boton = candidato
                        break
                except Exception:
                    pass
            if boton:
                break
        except Exception:
            continue

    if not boton:
        # Fallback
        xpaths_boton = [
            "//button[contains(., 'Nuevo cuatrimestre')]",
            "//button[.//span[contains(., 'Nuevo cuatrimestre')]]",
        ]
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
        raise NoSuchElementException("No se encontró el botón 'Nuevo cuatrimestre'.")

    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", boton)
    time.sleep(1)

    try:
        boton.click()
    except Exception:
        driver.execute_script("arguments[0].click();", boton)

    time.sleep(2)

    if not formulario_visible(driver):
        print("Primer intento no abrió el panel, reintentando con JavaScript...")
        driver.execute_script("arguments[0].click();", boton)
        time.sleep(2)

    esperar(driver, 8).until(lambda d: formulario_visible(d))
    print("Panel de cuatrimestre abierto correctamente.")


def obtener_input_no_cuatrimestre(driver):
    xpaths = [
        "//input[contains(@placeholder, 'No. de cuatrimestre')]",
        "//label[contains(., 'No. de Cuatrimestre')]/following::input[1]",
        "//input[contains(@wire:model, 'no_cuatrimestre')]",
        "//input[contains(@name, 'no_cuatrimestre') or contains(@id, 'no_cuatrimestre')]",
        "//input[@type='number']",
    ]

    for xpath in xpaths:
        try:
            elementos = driver.find_elements(By.XPATH, xpath)
            for elemento in elementos:
                if elemento.is_displayed() and elemento.is_enabled():
                    return elemento
        except Exception:
            continue

    raise NoSuchElementException("No se encontró el input de no_cuatrimestre.")


def obtener_input_nombre_cuatrimestre(driver):
    xpaths = [
        "//input[contains(@placeholder, 'Ej. Primer cuatrimestre')]",
        "//label[contains(., 'Nombre Cuatrimestre')]/following::input[1]",
        "//label[contains(., 'Nombre del cuatrimestre')]/following::input[1]",
        "//input[contains(@wire:model, 'nombre_cuatrimestre')]",
        "//input[contains(@name, 'nombre_cuatrimestre') or contains(@id, 'nombre_cuatrimestre')]",
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

    raise NoSuchElementException("No se encontró el input de nombre_cuatrimestre.")


def obtener_select_mes(driver):
    xpaths = [
        "//label[contains(., 'Selecciona los meses')]/following::select[1]",
        "//select[option[contains(., 'SEPTIEMBRE') or contains(., 'ENERO') or contains(., 'MAYO')]]",
        "//select[contains(@wire:model, 'mes_id')]",
        "(//select)[1]",
    ]

    for xpath in xpaths:
        try:
            elementos = driver.find_elements(By.XPATH, xpath)
            for elemento in elementos:
                if elemento.is_displayed() and elemento.is_enabled():
                    return elemento
        except Exception:
            continue

    raise NoSuchElementException("No se encontró el select de mes.")


def escribir_en_input(driver, elemento, texto):
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

    try:
        elemento.send_keys(str(texto))
    except Exception:
        driver.execute_script("""
            arguments[0].value = arguments[1];
            arguments[0].dispatchEvent(new Event('input', { bubbles: true }));
            arguments[0].dispatchEvent(new Event('change', { bubbles: true }));
        """, elemento, str(texto))

    time.sleep(1)
    valor_final = driver.execute_script("return arguments[0].value;", elemento)
    print(f"Valor capturado en input: {valor_final}")


def seleccionar_mes(driver, indice_opcion=1):
    """
    Selecciona el mes por índice.
    0 suele ser placeholder.
    1 a 3 son opciones válidas.
    """
    print(f"Seleccionando mes por índice: {indice_opcion}")

    select_mes = obtener_select_mes(driver)
    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", select_mes)
    time.sleep(1)

    try:
        combo = Select(select_mes)
        combo.select_by_index(indice_opcion)
        texto = combo.options[indice_opcion].text.strip()
        valor = combo.options[indice_opcion].get_attribute("value")
        print(f"Mes seleccionado con Select(): {valor} -> {texto}")
        time.sleep(1.5)
        return
    except Exception:
        print("No se pudo seleccionar con Select(), se intentará con JavaScript...")

    resultado = driver.execute_script("""
        const el = arguments[0];
        const indice = arguments[1];

        if (!el || !el.options) return null;
        if (indice < 0 || indice >= el.options.length) return null;

        const opt = el.options[indice];
        el.value = opt.value;
        el.dispatchEvent(new Event('input', { bubbles: true }));
        el.dispatchEvent(new Event('change', { bubbles: true }));

        return { value: opt.value, text: opt.text };
    """, select_mes, indice_opcion)

    if not resultado:
        raise Exception("No se pudo seleccionar el mes.")

    print(f"Mes seleccionado con JavaScript: {resultado['value']} -> {resultado['text']}")
    time.sleep(1.5)


def guardar_formulario(driver):
    print("Guardando formulario...")

    xpaths = [
        "//button[contains(., 'Guardar')]",
        "//button[@type='submit']",
    ]

    for xpath in xpaths:
        try:
            boton = esperar(driver, 4).until(
                EC.presence_of_element_located((By.XPATH, xpath))
            )
            if boton.is_displayed():
                driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", boton)
                time.sleep(1)
                try:
                    boton.click()
                except Exception:
                    driver.execute_script("arguments[0].click();", boton)
                time.sleep(3)
                return
        except Exception:
            continue

    raise NoSuchElementException("No se encontró el botón Guardar.")


def detectar_mensaje_exito(driver):
    try:
        html = driver.page_source.lower()
        return "cuatrimestre creado correctamente" in html
    except Exception:
        return False


def detectar_mensaje_duplicado(driver):
    try:
        html = driver.page_source.lower()
        return "el cuatrimestre ya existe" in html
    except Exception:
        return False


def detectar_error_nombre(driver):
    try:
        html = driver.page_source.lower()
        return "el campo nombre del cuatrimestre es obligatorio" in html
    except Exception:
        return False


def detectar_error_mes(driver):
    try:
        html = driver.page_source.lower()
        return "el campo mes es obligatorio" in html or "el mes seleccionado no es válido" in html
    except Exception:
        return False


# =========================================================
# FLUJO DE LA PRUEBA
# =========================================================
def llenar_formulario_cuatrimestre_correcto(driver, numero, nombre, indice_mes):
    print("Capturando datos del cuatrimestre...")
    abrir_panel_nuevo_cuatrimestre(driver)
    escribir_en_input(driver, obtener_input_no_cuatrimestre(driver), numero)
    escribir_en_input(driver, obtener_input_nombre_cuatrimestre(driver), nombre)
    seleccionar_mes(driver, indice_mes)


def caso_registro_correcto(driver):
    print("\nCaso 1: registro correcto")
    llenar_formulario_cuatrimestre_correcto(driver, "9", "9° Cuatrimestre", 3)
    guardar_formulario(driver)

    if detectar_mensaje_exito(driver):
        print("OK: se creó correctamente el cuatrimestre.")
        return True

    print("REVISAR: no se detectó claramente el mensaje de éxito.")
    return False


def caso_cuatrimestre_duplicado(driver):
    print("\nCaso 2: cuatrimestre duplicado")
    llenar_formulario_cuatrimestre_correcto(driver, "1", "Primero", 1)
    guardar_formulario(driver)

    if detectar_mensaje_duplicado(driver):
        print("OK: se detectó duplicidad.")
        return True

    print("REVISAR: no se detectó claramente la duplicidad.")
    return False


def caso_nombre_vacio(driver):
    print("\nCaso 3: nombre vacío")
    abrir_panel_nuevo_cuatrimestre(driver)
    escribir_en_input(driver, obtener_input_no_cuatrimestre(driver), "5")

    input_nombre = obtener_input_nombre_cuatrimestre(driver)
    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", input_nombre)
    time.sleep(0.5)

    try:
        input_nombre.click()
    except Exception:
        driver.execute_script("arguments[0].click();", input_nombre)

    driver.execute_script("""
        arguments[0].value = '';
        arguments[0].dispatchEvent(new Event('input', { bubbles: true }));
        arguments[0].dispatchEvent(new Event('change', { bubbles: true }));
    """, input_nombre)

    time.sleep(1)
    seleccionar_mes(driver, 2)
    guardar_formulario(driver)

    if detectar_error_nombre(driver):
        print("OK: se detectó validación del nombre.")
        return True

    print("REVISAR: no se detectó claramente la validación del nombre.")
    return False


def caso_mes_invalido(driver):
    print("\nCaso 4: mes no seleccionado o inválido")
    abrir_panel_nuevo_cuatrimestre(driver)
    escribir_en_input(driver, obtener_input_no_cuatrimestre(driver), "5")
    escribir_en_input(driver, obtener_input_nombre_cuatrimestre(driver), "Quinto")

    select_mes = obtener_select_mes(driver)

    try:
        Select(select_mes).select_by_index(0)
    except Exception:
        driver.execute_script("""
            const el = arguments[0];
            if (el && el.options && el.options.length > 0) {
                el.selectedIndex = 0;
                el.dispatchEvent(new Event('input', { bubbles: true }));
                el.dispatchEvent(new Event('change', { bubbles: true }));
            }
        """, select_mes)

    time.sleep(1)
    guardar_formulario(driver)

    if detectar_error_mes(driver):
        print("OK: se detectó error de validación del mes.")
        return True

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
                "Los casos ejecutados respondieron conforme a lo esperado."
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
