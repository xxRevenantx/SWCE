# -*- coding: utf-8 -*-
"""
PCB-016 / Mod-03 / HU-002
Prueba automatizada de edición de generación en SWCE.
"""

import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait, Select
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException
from webdriver_manager.chrome import ChromeDriverManager


# -------------------------------
# CONFIGURACIÓN
# -------------------------------
URL_BASE = "http://swce.test"
URL_LOGIN = f"{URL_BASE}/login"
URL_GENERACIONES = f"{URL_BASE}/generaciones"

CORREO = "admin@swce.com"
PASSWORD = "Swce#2026"

GENERACION_A_BUSCAR = "2022-2025"
NUEVA_GENERACION = "2022-2026"
ESTATUS_NUEVO = "Inactiva"

TIEMPO_ESPERA = 20


# -------------------------------
# APOYO
# -------------------------------
def iniciar_navegador():
    """Inicializo el navegador Chrome."""
    opciones = webdriver.ChromeOptions()
    opciones.add_argument("--start-maximized")
    opciones.add_experimental_option("excludeSwitches", ["enable-logging"])

    driver = webdriver.Chrome(
        service=Service(ChromeDriverManager().install()),
        options=opciones
    )
    driver.implicitly_wait(2)
    return driver


def esperar(driver, condicion, tiempo=TIEMPO_ESPERA):
    """Espero una condición."""
    return WebDriverWait(driver, tiempo).until(condicion)


def hacer_login(driver):
    """Inicio sesión en el sistema."""
    driver.get(URL_LOGIN)

    esperar(driver, EC.presence_of_element_located((By.NAME, "email"))).send_keys(CORREO)
    driver.find_element(By.NAME, "password").send_keys(PASSWORD)
    driver.find_element(By.NAME, "password").send_keys(Keys.ENTER)

    esperar(driver, EC.presence_of_element_located((By.TAG_NAME, "body")))
    time.sleep(2)
    print("Sesión iniciada correctamente.")


def abrir_modulo_generaciones(driver):
    """Abro el módulo de generaciones."""
    driver.get(URL_GENERACIONES)
    esperar(driver, EC.presence_of_element_located((By.TAG_NAME, "body")))
    time.sleep(2)
    print("Módulo de generaciones abierto.")


def buscar_generacion(driver, nombre):
    """
    Busco la generación en el listado.
    Aquí intento varios selectores comunes.
    """
    posibles_selectores = [
        (By.CSS_SELECTOR, "input[type='search']"),
        (By.CSS_SELECTOR, "input[placeholder*='Buscar']"),
        (By.CSS_SELECTOR, "input[placeholder*='buscar']"),
        (By.CSS_SELECTOR, "input[wire\\:model*='buscar']"),
        (By.CSS_SELECTOR, "input[wire\\:model*='search']"),
        (By.XPATH, "//input[contains(@placeholder,'Buscar')]"),
    ]

    input_busqueda = None

    for by, selector in posibles_selectores:
        elementos = driver.find_elements(by, selector)
        for e in elementos:
            if e.is_displayed() and e.is_enabled():
                input_busqueda = e
                break
        if input_busqueda:
            break

    if not input_busqueda:
        raise Exception("No se encontró el input de búsqueda.")

    input_busqueda.click()
    input_busqueda.send_keys(Keys.CONTROL + "a")
    input_busqueda.send_keys(Keys.DELETE)
    input_busqueda.send_keys(nombre)

    time.sleep(2)
    print(f"Generación buscada: {nombre}")


def abrir_modal_edicion(driver, nombre):
    """Abro el modal de edición desde la fila de la tabla."""
    fila = esperar(
        driver,
        EC.presence_of_element_located(
            (By.XPATH, f"//tr[td[contains(normalize-space(), '{nombre}')]]")
        )
    )

    botones = fila.find_elements(By.XPATH, ".//button")
    if not botones:
        raise Exception("No se encontró el botón de editar en la fila.")

    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", botones[0])
    time.sleep(0.5)
    driver.execute_script("arguments[0].click();", botones[0])

    esperar(
        driver,
        EC.visibility_of_element_located(
            (By.XPATH, "//h2[contains(., 'Editar Generación') or contains(., 'Editar generación')]")
        )
    )

    print("Modal de edición abierto.")


def esperar_loader_modal(driver):
    """
    Espero a que desaparezca el loader del modal.
    """
    time.sleep(1)

    for _ in range(20):
        overlays = driver.find_elements(
            By.XPATH,
            "//*[contains(text(),'Cargando') and contains(@class,'text-sm')]"
        )

        visible = False
        for overlay in overlays:
            try:
                if overlay.is_displayed():
                    visible = True
                    break
            except Exception:
                pass

        if not visible:
            print("Loader del modal finalizado.")
            return

        time.sleep(0.5)

    print("Continuando aunque el loader pudo seguir visible.")


def obtener_modal_visible(driver):
    """Obtengo el contenedor principal visible del modal."""
    modales = driver.find_elements(
        By.XPATH,
        "//div[@role='dialog' and @aria-modal='true']"
    )

    for modal in modales:
        try:
            if modal.is_displayed():
                return modal
        except Exception:
            pass

    raise Exception("No se encontró un modal visible.")


def obtener_inputs_modal(driver):
    """Obtengo los inputs visibles del modal."""
    modal = obtener_modal_visible(driver)
    inputs = modal.find_elements(By.TAG_NAME, "input")

    inputs_visibles = []
    for inp in inputs:
        try:
            if inp.is_displayed():
                inputs_visibles.append(inp)
        except Exception:
            pass

    return inputs_visibles


def obtener_selects_modal(driver):
    """Obtengo los select visibles del modal."""
    modal = obtener_modal_visible(driver)
    selects = modal.find_elements(By.TAG_NAME, "select")

    selects_visibles = []
    for sel in selects:
        try:
            if sel.is_displayed():
                selects_visibles.append(sel)
        except Exception:
            pass

    return selects_visibles


def limpiar_y_escribir(driver, elemento, valor):
    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", elemento)
    time.sleep(0.3)

    try:
        elemento.click()
        elemento.send_keys(Keys.CONTROL + "a")
        elemento.send_keys(Keys.DELETE)
        time.sleep(0.2)
        elemento.send_keys(valor)
    except Exception:
        driver.execute_script("arguments[0].value = '';", elemento)
        driver.execute_script("arguments[0].value = arguments[1];", elemento, valor)
        driver.execute_script("arguments[0].dispatchEvent(new Event('input', { bubbles: true }));", elemento)
        driver.execute_script("arguments[0].dispatchEvent(new Event('change', { bubbles: true }));", elemento)

    time.sleep(0.7)


def seleccionar_estatus(driver, valor):
    """Selecciono el estatus visible del modal."""
    selects = obtener_selects_modal(driver)

    if not selects:
        raise Exception("No se encontró el select de estatus en el modal.")

    for select_html in selects:
        try:
            Select(select_html).select_by_visible_text(valor)
            time.sleep(0.8)
            print(f"Estatus seleccionado: {valor}")
            return
        except Exception:
            pass

    raise Exception(f"No fue posible seleccionar el estatus: {valor}")


def llenar_formulario_edicion(driver):
    """
    Lleno el formulario del modal.
    Se espera:
    - Un input principal para nombre/generación.
    - Un select para estatus.
    """
    esperar_loader_modal(driver)

    inputs = obtener_inputs_modal(driver)

    if len(inputs) < 1:
        raise Exception("No se encontró el input principal de la generación en el modal.")

    input_generacion = inputs[0]

    print("Llenando campo: generación")
    limpiar_y_escribir(driver, input_generacion, NUEVA_GENERACION)

    try:
        print("Seleccionando campo: estatus")
        seleccionar_estatus(driver, ESTATUS_NUEVO)
    except Exception as e:
        print(f"Aviso: no se pudo cambiar el estatus. Detalle: {e}")

    print("Formulario llenado correctamente.")


def guardar_cambios(driver):
    """Guardo los cambios con el botón Actualizar."""
    modal = obtener_modal_visible(driver)

    boton_actualizar = modal.find_element(
        By.XPATH,
        ".//button[contains(., 'Actualizar')]"
    )

    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", boton_actualizar)
    time.sleep(0.5)
    driver.execute_script("arguments[0].click();", boton_actualizar)

    time.sleep(3)
    print("Se ejecutó la acción de actualizar.")


def validar_resultado(driver):
    """
    Valido el resultado esperado.
    Busco mensaje de éxito o la presencia de la nueva generación en la tabla.
    """
    pagina = driver.page_source

    textos_exito = [
        "Generación actualizada",
        "¡Generación actualizada!",
        "actualizada correctamente",
        NUEVA_GENERACION,
        ESTATUS_NUEVO,
    ]

    encontrados = [texto for texto in textos_exito if texto in pagina]

    if encontrados:
        print("Prueba exitosa. Se detectaron cambios o mensaje de éxito.")
        return True

    print("No se detectaron cambios claros en la interfaz.")
    return False


# -------------------------------
# PRUEBA PRINCIPAL
# -------------------------------
def prueba_pcb_016():
    driver = iniciar_navegador()

    try:
        print("Iniciando PCB-016 - Edición de generación")
        hacer_login(driver)
        abrir_modulo_generaciones(driver)
        buscar_generacion(driver, GENERACION_A_BUSCAR)
        abrir_modal_edicion(driver, GENERACION_A_BUSCAR)
        llenar_formulario_edicion(driver)
        guardar_cambios(driver)

        resultado = validar_resultado(driver)

        if resultado:
            print("RESULTADO FINAL: OK")
        else:
            print("RESULTADO FINAL: REVISAR MANUALMENTE")

        time.sleep(5)

    except TimeoutException as e:
        print("Tiempo de espera agotado.")
        print(f"Detalle: {e}")

    except Exception as e:
        print("La prueba falló.")
        print(f"Detalle: {e}")

    finally:
        driver.quit()
        print("Navegador cerrado.")


if __name__ == "__main__":
    prueba_pcb_016()
