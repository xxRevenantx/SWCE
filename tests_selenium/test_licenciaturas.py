from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time


# Configuración general
URL_LOGIN = "http://swce.test/login"
URL_LICENCIATURAS = "http://swce.test/licenciaturas"  # Ajusta si tu ruta es distinta

CORREO = "admin@swce.com"
CONTRASENA = "Swce#2026"

# Datos de prueba
NOMBRE = "Licenciatura de Prueba Selenium"
RVOE = "RVOE-SEL-001"
NOMBRE_CORTO = "LPS"
SLUG = "licenciatura-de-prueba-selenium"


def buscar_input(driver, wait, selectores):
    """
    Intenta encontrar un input usando varios selectores posibles.
    Retorna el primer elemento encontrado.
    """
    for by, selector in selectores:
        elementos = driver.find_elements(by, selector)
        if elementos:
            return elementos[0]

    raise Exception(f"No se encontró ningún elemento con los selectores: {selectores}")


def login(driver, wait):
    """
    Inicia sesión en el sistema.
    """
    driver.get(URL_LOGIN)

    email = wait.until(EC.visibility_of_element_located((By.NAME, "email")))
    password = wait.until(EC.visibility_of_element_located((By.NAME, "password")))

    email.clear()
    email.send_keys(CORREO)

    password.clear()
    password.send_keys(CONTRASENA)

    boton = wait.until(
        EC.element_to_be_clickable((By.CSS_SELECTOR, "button[type='submit']"))
    )
    boton.click()

    # Espero a salir del login
    wait.until(lambda d: "/login" not in d.current_url)


def abrir_modulo_licenciaturas(driver, wait):
    """
    Abre el módulo de licenciaturas.
    Si ya conoces la ruta exacta, la visita directamente.
    """
    driver.get(URL_LICENCIATURAS)

    # Espero que cargue el body al menos
    wait.until(EC.presence_of_element_located((By.TAG_NAME, "body")))


def abrir_formulario_crear(driver, wait):
    """
    Intenta abrir el formulario de crear licenciatura.
    Ajusta los selectores si tu botón tiene otro texto o atributo.
    """
    posibles_botones = [
        (By.XPATH, "//button[contains(., 'Crear')]"),
        (By.XPATH, "//button[contains(., 'Nueva')]"),
        (By.XPATH, "//button[contains(., 'Licenciatura')]"),
        (By.XPATH, "//a[contains(., 'Crear')]"),
        (By.CSS_SELECTOR, "[data-testid='btn-crear-licenciatura']"),
    ]

    for by, selector in posibles_botones:
        elementos = driver.find_elements(by, selector)
        if elementos:
            wait.until(EC.element_to_be_clickable((by, selector))).click()
            return

    # Si no encuentra botón, asume que el formulario ya está visible
    print("No se encontró botón de apertura. Se asume que el formulario ya está visible.")


def llenar_formulario(driver, wait):
    """
    Llena el formulario de crear licenciatura.
    """
    # Selectores posibles para cada input
    input_nombre = buscar_input(driver, wait, [
        (By.NAME, "nombre"),
        (By.CSS_SELECTOR, "input[wire\\:model='nombre']"),
        (By.ID, "nombre"),
    ])

    input_rvoe = buscar_input(driver, wait, [
        (By.NAME, "RVOE"),
        (By.CSS_SELECTOR, "input[wire\\:model='RVOE']"),
        (By.ID, "RVOE"),
    ])

    input_nombre_corto = buscar_input(driver, wait, [
        (By.NAME, "nombre_corto"),
        (By.CSS_SELECTOR, "input[wire\\:model='nombre_corto']"),
        (By.ID, "nombre_corto"),
    ])

    input_slug = buscar_input(driver, wait, [
        (By.NAME, "slug"),
        (By.CSS_SELECTOR, "input[wire\\:model='slug']"),
        (By.ID, "slug"),
    ])

    # Llenado
    input_nombre.clear()
    input_nombre.send_keys(NOMBRE)

    # Espero un poco por si Livewire genera automáticamente el slug
    time.sleep(1)

    input_rvoe.clear()
    input_rvoe.send_keys(RVOE)

    input_nombre_corto.clear()
    input_nombre_corto.send_keys(NOMBRE_CORTO)

    # Si quieres respetar el slug autogenerado, puedes omitir estas 2 líneas.
    input_slug.clear()
    input_slug.send_keys(SLUG)


def enviar_formulario(driver, wait):
    """
    Envía el formulario.
    """
    posibles_botones_guardar = [
        (By.XPATH, "//button[contains(., 'Guardar')]"),
        (By.XPATH, "//button[contains(., 'Crear')]"),
        (By.XPATH, "//button[contains(., 'Registrar')]"),
        (By.CSS_SELECTOR, "button[type='submit']"),
        (By.CSS_SELECTOR, "[data-testid='btn-guardar-licenciatura']"),
    ]

    for by, selector in posibles_botones_guardar:
        elementos = driver.find_elements(by, selector)
        if elementos:
            wait.until(EC.element_to_be_clickable((by, selector))).click()
            return

    raise Exception("No se encontró el botón para guardar la licenciatura.")


def validar_resultado(driver, wait):
    """
    Valida el resultado de la operación.
    Busca mensaje de éxito o presencia del nuevo registro.
    """
    wait.until(EC.presence_of_element_located((By.TAG_NAME, "body")))
    time.sleep(2)

    texto_visible = driver.find_element(By.TAG_NAME, "body").text

    condiciones_ok = [
        "Licenciatura creada correctamente" in texto_visible,
        NOMBRE in texto_visible,
    ]

    if any(condiciones_ok):
        print("Prueba correcta: la licenciatura fue creada.")
        print("URL actual:", driver.current_url)
        driver.save_screenshot("evidencia_licenciatura_creada.png")
        print("Captura guardada: evidencia_licenciatura_creada.png")
    else:
        raise Exception("No se detectó mensaje de éxito ni el nuevo registro en pantalla.")


def main():
    driver = webdriver.Chrome()
    wait = WebDriverWait(driver, 20)

    try:
        print("Paso 1: Iniciar sesión")
        login(driver, wait)

        print("Paso 2: Abrir módulo de licenciaturas")
        abrir_modulo_licenciaturas(driver, wait)

        print("Paso 3: Abrir formulario de crear")
        abrir_formulario_crear(driver, wait)

        print("Paso 4: Llenar formulario")
        llenar_formulario(driver, wait)

        print("Paso 5: Enviar formulario")
        enviar_formulario(driver, wait)

        print("Paso 6: Validar resultado")
        validar_resultado(driver, wait)

    except Exception as e:
        print("Ocurrió un error:", e)
        print("URL actual:", driver.current_url)
        print("Título:", driver.title)
        print("Texto visible:")
        print(driver.find_element(By.TAG_NAME, "body").text)
        driver.save_screenshot("evidencia_licenciatura_error.png")
        print("Captura guardada: evidencia_licenciatura_error.png")

    finally:
        input("Presiona Enter para cerrar...")
        driver.quit()


if __name__ == "__main__":
    main()
