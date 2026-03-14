# Importo las herramientas necesarias de Selenium.
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

# Defino la URL base del sistema.
BASE_URL = "http://swce.test"

# Creo el navegador.
driver = webdriver.Chrome()
wait = WebDriverWait(driver, 10)

try:
    # Abro el login.
    driver.get(f"{BASE_URL}/login")

    # Espero a que cargue el input del correo.
    wait.until(EC.presence_of_element_located((By.NAME, "email")))

    # Lleno los campos del formulario.
    driver.find_element(By.NAME, "email").send_keys("swce@gmail.com")
    driver.find_element(By.NAME, "password").send_keys("Swce#2026")

    # Doy clic en el botón iniciar sesión.
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()

    # Espero un momento a que cambie la página o procese el login.
    wait.until(EC.presence_of_element_located((By.TAG_NAME, "body")))

    # Muestro la URL actual para revisar a dónde mandó el sistema.
    print("URL actual:", driver.current_url)

    # Muestro parte del contenido visible para revisar mensajes.
    print("Título:", driver.title)
    print("Texto visible:")
    print(driver.find_element(By.TAG_NAME, "body").text)

    input("Presiona Enter para cerrar el navegador...")

except Exception as e:
    print("Ocurrió un error:")
    print(type(e).__name__)
    print(str(e))
    print("URL al fallar:", driver.current_url)
    input("Presiona Enter para cerrar el navegador...")

finally:
    driver.quit()
