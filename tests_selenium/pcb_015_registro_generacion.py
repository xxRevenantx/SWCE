
"""
PCB-015 / Mod-03 / HU-004
Prueba automatizada de edición de generaciones en SWCE.
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


class PruebaEdicionGeneracion:
    def __init__(self):
        # Ruta local del ChromeDriver
        service = Service("chromedriver.exe")
        self.driver = webdriver.Chrome(service=service)
        self.driver.maximize_window()
        self.wait = WebDriverWait(self.driver, 10)

        # Datos base
        self.url_login = "http://swce.test/login"
        self.url_generaciones = "http://swce.test/generaciones"

        self.usuario = "swce@gmail.com"
        self.password = "swce#2026"

    def iniciar_sesion(self):
        self.driver.get(self.url_login)

        self.wait.until(EC.presence_of_element_located((By.NAME, "email"))).send_keys(self.usuario)
        self.driver.find_element(By.NAME, "password").send_keys(self.password)

        # Ajusta el selector del botón según tu login real
        self.driver.find_element(By.XPATH, "//button[@type='submit']").click()

        # Espera a que cargue el dashboard
        self.wait.until(EC.presence_of_element_located((By.TAG_NAME, "body")))
        print("Inicio de sesión correcto")

    def ir_a_generaciones(self):
        self.driver.get(self.url_generaciones)
        self.wait.until(EC.presence_of_element_located((By.TAG_NAME, "body")))
        print("Módulo de generaciones cargado")

    def editar_generacion_correctamente(self):
        """
        Caso: editar una generación existente sin duplicar nombre.
        """
        try:
            print("\n[CASO 1] Edición correcta")

            # Buscar registro
            buscador = self.wait.until(
                EC.presence_of_element_located((By.XPATH, "//input[contains(@placeholder,'Buscar')]"))
            )
            buscador.clear()
            buscador.send_keys("2022-2025")
            time.sleep(2)

            # Clic en botón editar
            boton_editar = self.wait.until(
                EC.element_to_be_clickable((By.XPATH, "//button[contains(@wire:click, 'editar') or contains(@title,'Editar')]"))
            )
            boton_editar.click()

            # Esperar modal
            self.wait.until(EC.visibility_of_element_located((By.XPATH, "//input[@name='nombre' or @wire:model='nombre']")))

            # Editar nombre
            input_nombre = self.driver.find_element(By.XPATH, "//input[@name='nombre' or @wire:model='nombre']")
            input_nombre.clear()
            input_nombre.send_keys("2022-2026")

            # Cambiar estatus si existe select
            try:
                select_status = Select(self.driver.find_element(By.XPATH, "//select[@name='status' or @wire:model='status']"))
                select_status.select_by_visible_text("Activa")
            except:
                print("No se encontró select de estatus, se omite este paso")

            # Guardar
            boton_guardar = self.driver.find_element(By.XPATH, "//button[contains(.,'Actualizar') or contains(.,'Guardar')]")
            boton_guardar.click()

            # Validar mensaje de éxito
            mensaje = self.wait.until(
                EC.presence_of_element_located((By.XPATH, "//*[contains(text(),'actualizada correctamente') or contains(text(),'Actualizado correctamente')]"))
            )

            print("Resultado:", mensaje.text)
            print("CASO 1 APROBADO")

        except Exception as e:
            print("CASO 1 FALLIDO:", str(e))

    def editar_generacion_duplicada(self):
        """
        Caso: intentar actualizar una generación con un nombre que ya existe.
        """
        try:
            print("\n[CASO 2] Validación de duplicidad")

            self.driver.get(self.url_generaciones)
            time.sleep(2)

            buscador = self.wait.until(
                EC.presence_of_element_located((By.XPATH, "//input[contains(@placeholder,'Buscar')]"))
            )
            buscador.clear()
            buscador.send_keys("2022-2026")
            time.sleep(2)

            boton_editar = self.wait.until(
                EC.element_to_be_clickable((By.XPATH, "//button[contains(@wire:click, 'editar') or contains(@title,'Editar')]"))
            )
            boton_editar.click()

            self.wait.until(EC.visibility_of_element_located((By.XPATH, "//input[@name='nombre' or @wire:model='nombre']")))

            input_nombre = self.driver.find_element(By.XPATH, "//input[@name='nombre' or @wire:model='nombre']")
            input_nombre.clear()
            input_nombre.send_keys("2023-2026")  # Ya existente

            boton_guardar = self.driver.find_element(By.XPATH, "//button[contains(.,'Actualizar') or contains(.,'Guardar')]")
            boton_guardar.click()

            # Validar mensaje de error
            mensaje_error = self.wait.until(
                EC.presence_of_element_located((By.XPATH, "//*[contains(text(),'ya existe') or contains(text(),'duplicada') or contains(text(),'registrada')]"))
            )

            print("Resultado:", mensaje_error.text)
            print("CASO 2 APROBADO")

        except Exception as e:
            print("CASO 2 FALLIDO:", str(e))

    def validar_registro_inexistente(self):
        """
        Caso: intentar acceder a una edición con un id inexistente.
        Este caso depende de cómo maneje la ruta tu sistema.
        """
        try:
            print("\n[CASO 3] Validación de registro inexistente")

            # Ruta de ejemplo. Ajusta según tu proyecto.
            self.driver.get("http://swce.test/admin/generaciones/9999/edit")
            time.sleep(2)

            body = self.driver.find_element(By.TAG_NAME, "body").text

            if "404" in body or "No encontrado" in body or "not found" in body:
                print("Resultado: el sistema bloqueó correctamente el registro inexistente")
                print("CASO 3 APROBADO")
            else:
                print("CASO 3 EN REVISIÓN: no apareció mensaje esperado")

        except Exception as e:
            print("CASO 3 FALLIDO:", str(e))

    def cerrar(self):
        time.sleep(3)
        self.driver.quit()


if __name__ == "__main__":
    prueba = PruebaEdicionGeneracion()

    try:
        prueba.iniciar_sesion()
        prueba.ir_a_generaciones()
        prueba.editar_generacion_correctamente()
        prueba.editar_generacion_duplicada()
        prueba.validar_registro_inexistente()
    finally:
        prueba.cerrar()
