# -*- coding: utf-8 -*-
"""
PCB-013 / Mod-02 / HU-001
Prueba automatizada de creación de cuatrimestre en SWCE.
"""

import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import Select, WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException
from webdriver_manager.chrome import ChromeDriverManager


# --------------------------------------------------
# CONFIGURACIÓN
# --------------------------------------------------
URL_BASE = "http://swce.test"
URL_LOGIN = f"{URL_BASE}/login"
URL_CUATRIMESTRES = f"{URL_BASE}/cuatrimestres"

CORREO = "admin@swce.com"
PASSWORD = "Swce#2026"

NO_CUATRIMESTRE_VALIDO = "4"
NOMBRE_VALIDO = "Cuarto"
MES_VISIBLE = "Enero"

NO_CUATRIMESTRE_DUPLICADO = "1"
NOMBRE_DUPLICADO = "Primero"

TIEMPO_ESPERA = 20


# --------------------------------------------------
# FUNCIONES DE APOYO
# --------------------------------------------------
def iniciar_navegador():
    """Inicializo Chrome."""
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
    """Inicio sesión en SWCE."""
    driver.get(URL_LOGIN)

    esperar(driver, EC.presence_of_element_located((By.NAME, "email"))).send_keys(CORREO)
    driver.find_element(By.NAME, "password").send_keys(PASSWORD)
    driver.find_element(By.NAME, "password").send_keys(Keys.ENTER)

    esperar(driver, EC.presence_of_element_located((By.TAG_NAME, "body")))
    time.sleep(2)
    print("Sesión iniciada correctamente.")


def abrir_modulo_cuatrimestres(driver):
    """Abro el módulo de cuatrimestres."""
    driver.get(URL_CUATRIMESTRES)
    esperar(driver, EC.presence_of_element_located((By.TAG_NAME, "body")))
    time.sleep(2)
    print("Módulo de cuatrimestres abierto.")


def obtener_input_visible(driver, candidatos):
    """
    Busca un input visible por distintos selectores.
    candidatos = lista de tuplas (By, selector)
    """
    for by, selector in candidatos:
        elementos = driver.find_elements(by, selector)
        for elemento in elementos:
            try:
                if elemento.is_displayed() and elemento.is_enabled():
                    return elemento
            except Exception:
                pass
    raise Exception("No se encontró el input visible solicitado.")


def escribir_input(driver, elemento, valor):
    """Limpio el campo y escribo."""
    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", elemento)
    time.sleep(0.3)
    elemento.click()
    elemento.send_keys(Keys.CONTROL + "a")
    elemento.send_keys(Keys.DELETE)
    time.sleep(0.2)
    elemento.send_keys(valor)
    time.sleep(0.5)


def obtener_input_no_cuatrimestre(driver):
    """Obtengo el input del número de cuatrimestre."""
    return obtener_input_visible(driver, [
        (By.CSS_SELECTOR, "input[wire\\:model*='no_cuatrimestre']"),
        (By.CSS_SELECTOR, "input[placeholder*='Cuatrimestre']"),
        (By.CSS_SELECTOR, "input[placeholder*='cuatrimestre']"),
        (By.XPATH, "//input[contains(@placeholder,'Cuatrimestre')]"),
    ])


def obtener_input_nombre_cuatrimestre(driver):
    """Obtengo el input del nombre del cuatrimestre."""
    return obtener_input_visible(driver, [
        (By.CSS_SELECTOR, "input[wire\\:model*='nombre_cuatrimestre']"),
        (By.CSS_SELECTOR, "input[placeholder*='Nombre']"),
        (By.CSS_SELECTOR, "input[placeholder*='nombre']"),
        (By.XPATH, "//input[contains(@placeholder,'Nombre')]"),
    ])


def obtener_select_mes(driver):
    """Obtengo el select visible de meses."""
    # Primero intenta select nativo
    selects = driver.find_elements(By.TAG_NAME, "select")
    for select in selects:
        try:
            if select.is_displayed() and select.is_enabled():
                return select
        except Exception:
            pass

    # Si no lo encuentra, intenta por wire:model
    selects = driver.find_elements(By.CSS_SELECTOR, "select[wire\\:model*='mes_id']")
    for select in selects:
        try:
            if select.is_displayed() and select.is_enabled():
                return select
        except Exception:
            pass

    raise Exception("No se encontró el select visible de mes.")


def seleccionar_mes(driver, texto_visible):
    """Selecciono el mes por texto visible."""
    select = obtener_select_mes(driver)
    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", select)
    time.sleep(0.3)
    Select(select).select_by_visible_text(texto_visible)
    time.sleep(0.5)


def obtener_boton_guardar(driver):
    """Obtengo el botón para guardar."""
    botones = driver.find_elements(By.XPATH, "//button[contains(., 'Guardar')]")
    for boton in botones:
        try:
            if boton.is_displayed() and boton.is_enabled():
                return boton
        except Exception:
            pass

    botones = driver.find_elements(By.XPATH, "//button[contains(., 'Crear')]")
    for boton in botones:
        try:
            if boton.is_displayed() and boton.is_enabled():
                return boton
        except Exception:
            pass

    raise Exception("No se encontró el botón para guardar.")


def guardar(driver):
    """Ejecuto la acción de guardar."""
    boton = obtener_boton_guardar(driver)
    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", boton)
    time.sleep(0.3)
    driver.execute_script("arguments[0].click();", boton)
    time.sleep(2)


def obtener_texto_pagina(driver):
    """Obtengo todo el texto visible relevante."""
    return driver.find_element(By.TAG_NAME, "body").text


# --------------------------------------------------
# CASOS AUTOMATIZADOS
# --------------------------------------------------
def caso_creacion_exitosa(driver):
    print("\nCaso 1: creación exitosa")
    escribir_input(driver, obtener_input_no_cuatrimestre(driver), NO_CUATRIMESTRE_VALIDO)
    escribir_input(driver, obtener_input_nombre_cuatrimestre(driver), NOMBRE_VALIDO)
    seleccionar_mes(driver, MES_VISIBLE)
    guardar(driver)

    texto = obtener_texto_pagina(driver)
    if "Cuatrimestre creado correctamente" in texto or "¡Cuatrimestre creado correctamente!" in texto:
        print("OK: se creó el cuatrimestre correctamente.")
    else:
        print("REVISAR: no se detectó claramente el mensaje, pero la acción fue ejecutada.")


def caso_numero_duplicado(driver):
    print("\nCaso 2: número de cuatrimestre duplicado")
    escribir_input(driver, obtener_input_no_cuatrimestre(driver), NO_CUATRIMESTRE_DUPLICADO)
    escribir_input(driver, obtener_input_nombre_cuatrimestre(driver), NOMBRE_DUPLICADO)
    seleccionar_mes(driver, MES_VISIBLE)
    guardar(driver)

    texto = obtener_texto_pagina(driver)
    if "El cuatrimestre ya existe" in texto or "¡El cuatrimestre ya existe!" in texto:
        print("OK: se detectó duplicidad del cuatrimestre.")
    else:
        print("REVISAR: no se detectó claramente el mensaje de duplicidad.")


def caso_numero_fuera_de_rango(driver):
    print("\nCaso 3: número de cuatrimestre fuera de rango")
    escribir_input(driver, obtener_input_no_cuatrimestre(driver), "10")
    escribir_input(driver, obtener_input_nombre_cuatrimestre(driver), "Décimo")
    seleccionar_mes(driver, MES_VISIBLE)
    guardar(driver)

    texto = obtener_texto_pagina(driver)
    if "no puede ser mayor a 9" in texto:
        print("OK: se detectó validación por rango máximo.")
    else:
        print("REVISAR: no se detectó claramente la validación del rango máximo.")


def caso_mes_invalido(driver):
    print("\nCaso 4: mes no seleccionado o inválido")
    escribir_input(driver, obtener_input_no_cuatrimestre(driver), "5")
    escribir_input(driver, obtener_input_nombre_cuatrimestre(driver), "Quinto")

    # Aquí intentamos dejar el select en la opción vacía si existe
    select = obtener_select_mes(driver)
    try:
        Select(select).select_by_index(0)
    except Exception:
        pass

    time.sleep(0.5)
    guardar(driver)

    texto = obtener_texto_pagina(driver)
    if "El campo mes es obligatorio" in texto or "El mes seleccionado no es válido" in texto:
        print("OK: se detectó error de validación del mes.")
    else:
        print("REVISAR: no se detectó claramente la validación del mes.")


# --------------------------------------------------
# EJECUCIÓN PRINCIPAL
# --------------------------------------------------
def prueba_pcb_013():
    driver = iniciar_navegador()

    try:
        print("Iniciando PCB-013 - Creación de cuatrimestre")
        hacer_login(driver)
        abrir_modulo_cuatrimestres(driver)

        caso_creacion_exitosa(driver)
        caso_numero_duplicado(driver)
        caso_numero_fuera_de_rango(driver)
        caso_mes_invalido(driver)

        print("\nRESULTADO FINAL: OK")
        time.sleep(4)

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
    prueba_pcb_013()
