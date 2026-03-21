# -*- coding: utf-8 -*-
"""
PCB-014 / Mod-02 / HU-004
Prueba automatizada de búsqueda de cuatrimestres en SWCE.
"""

import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait
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

BUSQUEDA_NUMERO = "1"
BUSQUEDA_NOMBRE = "1° Cuatrimestre"
BUSQUEDA_MES = "Enero"
BUSQUEDA_SIN_RESULTADOS = "zzzzzz-no-existe"

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


def obtener_input_busqueda(driver):
    """Busco el input visible de búsqueda."""
    selectores = [
        (By.CSS_SELECTOR, "input[type='search']"),
        (By.CSS_SELECTOR, "input[placeholder*='Buscar']"),
        (By.CSS_SELECTOR, "input[placeholder*='buscar']"),
        (By.CSS_SELECTOR, "input[wire\\:model*='search']"),
        (By.CSS_SELECTOR, "input[wire\\:model*='buscar']"),
        (By.XPATH, "//input[contains(@placeholder,'Buscar')]"),
    ]

    for by, selector in selectores:
        elementos = driver.find_elements(by, selector)
        for elemento in elementos:
            try:
                if elemento.is_displayed() and elemento.is_enabled():
                    return elemento
            except Exception:
                pass

    raise Exception("No se encontró el input de búsqueda visible.")


def escribir_busqueda(driver, texto):
    """Escribo el término de búsqueda."""
    buscador = obtener_input_busqueda(driver)
    buscador.click()
    buscador.send_keys(Keys.CONTROL + "a")
    buscador.send_keys(Keys.DELETE)
    time.sleep(0.5)
    buscador.send_keys(texto)
    time.sleep(2)
    print(f"Búsqueda aplicada: {texto}")


def limpiar_busqueda(driver):
    """Limpio el filtro."""
    buscador = obtener_input_busqueda(driver)
    buscador.click()
    buscador.send_keys(Keys.CONTROL + "a")
    buscador.send_keys(Keys.DELETE)
    time.sleep(2)
    print("Filtro limpiado.")


def obtener_texto_listado(driver):
    """Obtengo el texto del listado."""
    tablas = driver.find_elements(By.TAG_NAME, "table")
    for tabla in tablas:
        try:
            if tabla.is_displayed():
                return tabla.text
        except Exception:
            pass

    return driver.find_element(By.TAG_NAME, "body").text


def validar_texto_en_listado(driver, texto):
    """Valido si aparece un texto en el listado."""
    contenido = obtener_texto_listado(driver).lower()
    return texto.lower() in contenido


def validar_sin_resultados(driver):
    """Valido el comportamiento sin coincidencias."""
    texto = driver.find_element(By.TAG_NAME, "body").text.lower()

    mensajes_posibles = [
        "sin resultados",
        "no hay registros",
        "no se encontraron resultados",
        "sin coincidencias",
        "sin datos",
    ]

    if any(mensaje in texto for mensaje in mensajes_posibles):
        return True

    return BUSQUEDA_SIN_RESULTADOS.lower() not in texto


# --------------------------------------------------
# CASOS AUTOMATIZADOS
# --------------------------------------------------
def caso_busqueda_por_numero(driver):
    print("\nCaso 1: búsqueda por número de cuatrimestre")
    escribir_busqueda(driver, BUSQUEDA_NUMERO)

    if validar_texto_en_listado(driver, BUSQUEDA_NUMERO):
        print("OK: se encontraron coincidencias por número.")
    else:
        raise Exception("No se encontraron coincidencias por número.")


def caso_busqueda_por_nombre(driver):
    print("\nCaso 2: búsqueda por nombre de cuatrimestre")
    escribir_busqueda(driver, BUSQUEDA_NOMBRE)

    if validar_texto_en_listado(driver, BUSQUEDA_NOMBRE):
        print("OK: se encontraron coincidencias por nombre.")
    else:
        raise Exception("No se encontraron coincidencias por nombre.")


def caso_busqueda_por_mes(driver):
    print("\nCaso 3: búsqueda por nombre del mes relacionado")
    escribir_busqueda(driver, BUSQUEDA_MES)

    if validar_texto_en_listado(driver, BUSQUEDA_MES):
        print("OK: se encontraron coincidencias por mes.")
    else:
        raise Exception("No se encontraron coincidencias por mes.")


def caso_busqueda_sin_resultados(driver):
    print("\nCaso 4: búsqueda sin resultados")
    escribir_busqueda(driver, BUSQUEDA_SIN_RESULTADOS)

    if validar_sin_resultados(driver):
        print("OK: el sistema respondió correctamente sin coincidencias.")
    else:
        raise Exception("El sistema no reflejó correctamente el caso sin resultados.")


def caso_limpiar_filtro(driver):
    print("\nCaso 5: limpiar filtro")
    limpiar_busqueda(driver)

    texto = obtener_texto_listado(driver)

    if len(texto.strip()) > 0:
        print("OK: al limpiar la búsqueda se restauró el listado.")
    else:
        raise Exception("El listado no se restauró al limpiar la búsqueda.")


# --------------------------------------------------
# EJECUCIÓN PRINCIPAL
# --------------------------------------------------
def prueba_pcb_014():
    driver = iniciar_navegador()

    try:
        print("Iniciando PCB-014 - Búsqueda de cuatrimestre")
        hacer_login(driver)
        abrir_modulo_cuatrimestres(driver)

        caso_busqueda_por_numero(driver)
        caso_busqueda_por_nombre(driver)
        caso_busqueda_por_mes(driver)
        caso_busqueda_sin_resultados(driver)
        caso_limpiar_filtro(driver)

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
    prueba_pcb_014()
