from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

driver = webdriver.Chrome()
wait = WebDriverWait(driver, 10)

try:
    driver.get("http://swce.test/login")

    wait.until(EC.presence_of_element_located((By.NAME, "email")))

    driver.find_element(By.NAME, "email").clear()
    driver.find_element(By.NAME, "email").send_keys("admin@swce.com")

    driver.find_element(By.NAME, "password").clear()
    driver.find_element(By.NAME, "password").send_keys("Swce#2026")

    boton = wait.until(
        EC.element_to_be_clickable((By.CSS_SELECTOR, "button[type='submit']"))
    )
    boton.click()

    wait.until(lambda d: "/login" not in d.current_url)

    print("Login correcto")
    print("URL actual:", driver.current_url)

    input("Presiona Enter para cerrar...")

except Exception as e:
    print("Ocurrió un error:", e)
    print("URL actual:", driver.current_url)
    print("Texto visible:")
    print(driver.find_element(By.TAG_NAME, "body").text)
    input("Presiona Enter para cerrar...")

finally:
    driver.quit()
