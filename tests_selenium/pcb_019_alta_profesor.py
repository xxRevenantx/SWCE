"""
PCB-019 / Mod-05 / HU-001
Prueba automatizada de alta de profesor en SWCE.
"""

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait, Select
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, NoSuchElementException
from webdriver_manager.chrome import ChromeDriverManager
import time
import os


# =========================================================
# CONFIGURACIÓN GENERAL
# =========================================================
BASE_URL = "http://swce.test"
EMAIL = "admin@swce.com"
PASSWORD = "Swce#2026"

# Ajusta esta ruta si tu módulo usa otra URL
RUTA_PROFESORES = "/profesores"

# Ruta opcional para prueba con imagen
RUTA_IMAGEN_PRUEBA = r"C:\laragon\www\swce\tests_selenium\imagenes\profesor.png"


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
    driver.get(f"{BASE_URL}/login")

    wait = esperar(driver)

    print("Capturando credenciales...")
    wait.until(EC.presence_of_element_located((By.NAME, "email"))).send_keys(EMAIL)
    driver.find_element(By.NAME, "password").send_keys(PASSWORD)

    print("Enviando formulario de acceso...")
    driver.find_element(
        By.XPATH,
        "//button[contains(., 'Iniciar sesión') or contains(., 'Entrar') or contains(., 'Acceder')]"
    ).click()

    time.sleep(2)
    print("Login realizado.")


def abrir_modulo_profesores(driver):
    """Abre el módulo de profesores."""
    print("Abriendo módulo de profesores...")
    driver.get(f"{BASE_URL}{RUTA_PROFESORES}")
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
def abrir_panel_nuevo_profesor(driver):
    """Abre el panel o modal de alta de profesor."""
    print("Abriendo panel 'Nuevo profesor'...")

    boton = esperar(driver).until(
        EC.element_to_be_clickable(
            (By.XPATH, "//button[contains(., 'Nuevo profesor')]")
        )
    )
    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", boton)
    time.sleep(1)
    driver.execute_script("arguments[0].click();", boton)
    time.sleep(2)


def obtener_elemento_por_model(driver, modelo):
    """
    Busca el campo por varias estrategias.
    Primero intenta por atributos Livewire y después por atributos HTML normales.
    """
    xpaths = []

    if modelo == "CURP":
        xpaths = [
            "//label[contains(., 'CURP')]/following::input[1]",
            "//input[contains(@placeholder, 'CURP')]",
            "//input[contains(@name, 'CURP') or contains(@id, 'CURP')]",
            "//input[contains(@name, 'curp') or contains(@id, 'curp')]",
            "//*[@wire:model='CURP']",
            "//*[@wire:model.live='CURP']",
            "//*[contains(@wire:model, 'CURP')]",
            "//*[contains(@wire:model.live, 'CURP')]",
        ]

    elif modelo == "nombre":
        xpaths = [
            "//label[contains(., 'Nombre')]/following::input[1]",
            "//input[contains(@placeholder, 'Nombre')]",
            "//input[contains(@name, 'nombre') or contains(@id, 'nombre')]",
            "//*[@wire:model='nombre']",
            "//*[@wire:model.live='nombre']",
            "//*[contains(@wire:model, 'nombre')]",
            "//*[contains(@wire:model.live, 'nombre')]",
        ]

    elif modelo == "apellido_paterno":
        xpaths = [
            "//label[contains(., 'Apellido paterno')]/following::input[1]",
            "//input[contains(@placeholder, 'Apellido paterno')]",
            "//input[contains(@name, 'apellido_paterno') or contains(@id, 'apellido_paterno')]",
            "//*[@wire:model='apellido_paterno']",
            "//*[@wire:model.live='apellido_paterno']",
            "//*[contains(@wire:model, 'apellido_paterno')]",
            "//*[contains(@wire:model.live, 'apellido_paterno')]",
        ]

    elif modelo == "apellido_materno":
        xpaths = [
            "//label[contains(., 'Apellido materno')]/following::input[1]",
            "//input[contains(@placeholder, 'Apellido materno')]",
            "//input[contains(@name, 'apellido_materno') or contains(@id, 'apellido_materno')]",
            "//*[@wire:model='apellido_materno']",
            "//*[@wire:model.live='apellido_materno']",
            "//*[contains(@wire:model, 'apellido_materno')]",
            "//*[contains(@wire:model.live, 'apellido_materno')]",
        ]

    elif modelo == "telefono":
        xpaths = [
            "//label[contains(., 'Teléfono')]/following::input[1]",
            "//input[contains(@placeholder, 'Teléfono')]",
            "//input[contains(@name, 'telefono') or contains(@id, 'telefono')]",
            "//*[@wire:model='telefono']",
            "//*[@wire:model.live='telefono']",
            "//*[contains(@wire:model, 'telefono')]",
            "//*[contains(@wire:model.live, 'telefono')]",
        ]

    elif modelo == "perfil":
        xpaths = [
            "//label[contains(., 'Perfil')]/following::textarea[1]",
            "//label[contains(., 'Perfil')]/following::input[1]",
            "//textarea[contains(@name, 'perfil') or contains(@id, 'perfil')]",
            "//input[contains(@name, 'perfil') or contains(@id, 'perfil')]",
            "//*[@wire:model='perfil']",
            "//*[@wire:model.live='perfil']",
            "//*[contains(@wire:model, 'perfil')]",
            "//*[contains(@wire:model.live, 'perfil')]",
        ]

    else:
        xpaths = [
            f"//*[@wire:model='{modelo}']",
            f"//*[@wire:model.live='{modelo}']",
            f"//*[contains(@wire:model, '{modelo}')]",
            f"//*[contains(@wire:model.live, '{modelo}')]",
            f"//input[contains(@name, '{modelo}') or contains(@id, '{modelo}')]",
            f"//textarea[contains(@name, '{modelo}') or contains(@id, '{modelo}')]",
            f"//select[contains(@name, '{modelo}') or contains(@id, '{modelo}')]",
        ]

    for xpath in xpaths:
        try:
            elemento = esperar(driver, 4).until(
                EC.presence_of_element_located((By.XPATH, xpath))
            )
            return elemento
        except Exception:
            continue

    raise NoSuchElementException(f"No se encontró el campo con modelo: {modelo}")

def obtener_select_usuario(driver):
    """
    Busca el select real del campo Usuario sin depender
    totalmente de wire:model, porque Flux puede renderizarlo distinto.
    """
    xpaths = [
        "//label[contains(., 'Usuario')]/following::select[1]",
        "//select[option[contains(., 'Seleccione un usuario')]]",
        "//select[option[contains(., 'Selecciona un usuario')]]",
        "(//select)[1]",
    ]

    for xpath in xpaths:
        try:
            elemento = esperar(driver, 4).until(
                EC.presence_of_element_located((By.XPATH, xpath))
            )
            return elemento
        except Exception:
            continue

    raise NoSuchElementException("No se encontró el select real de Usuario.")


def seleccionar_usuario(driver):
    """Selecciona la primera opción válida del select Usuario."""
    print("Seleccionando usuario disponible...")

    select_usuario = obtener_select_usuario(driver)
    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", select_usuario)
    time.sleep(1)

    # Intento 1: usar Select normal
    try:
        combo = Select(select_usuario)
        for opcion in combo.options:
            valor = opcion.get_attribute("value")
            texto = opcion.text.strip()
            if valor and texto:
                combo.select_by_value(valor)
                print(f"Usuario seleccionado con Select(): {valor} -> {texto}")
                time.sleep(1.5)
                return
    except Exception:
        print("No se pudo seleccionar con Select(), se intentará con JavaScript...")

    # Intento 2: usar JavaScript
    try:
        valor = driver.execute_script("""
            const el = arguments[0];
            if (!el || !el.options) return null;

            for (let i = 0; i < el.options.length; i++) {
                const opt = el.options[i];
                if (opt.value && opt.text.trim() !== '') {
                    el.value = opt.value;
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                    return opt.value;
                }
            }
            return null;
        """, select_usuario)

        if valor:
            print(f"Usuario seleccionado con JavaScript. Valor: {valor}")
            time.sleep(1.5)
            return
    except Exception as e:
        print(f"Falló el intento con JavaScript: {e}")

    raise Exception("No se pudo seleccionar un usuario en el select de Usuario.")


def escribir_en_campo(driver, modelo, texto):
    """
    Escribe en un campo y espera a que sea visible e interactuable.
    """
    campo = obtener_elemento_por_model(driver, modelo)

    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", campo)
    time.sleep(1)

    esperar(driver, 5).until(EC.visibility_of(campo))
    esperar(driver, 5).until(lambda d: campo.is_enabled())

    campo.clear()
    time.sleep(0.3)
    campo.send_keys(texto)
    time.sleep(1)


def asignar_color(driver, valor_hex="#2563eb"):
    """Asigna color al input type=color o al campo relacionado."""
    print("Asignando color...")

    posibles_xpaths = [
        "//*[@wire:model='color']",
        "//*[@wire:model.live='color']",
        "//input[@type='color']",
        "//label[contains(., 'Color')]/following::input[1]",
    ]

    for xpath in posibles_xpaths:
        try:
            campo = esperar(driver, 2).until(
                EC.presence_of_element_located((By.XPATH, xpath))
            )
            driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", campo)
            driver.execute_script("arguments[0].value = arguments[1];", campo, valor_hex)
            driver.execute_script("arguments[0].dispatchEvent(new Event('input', { bubbles: true }));", campo)
            driver.execute_script("arguments[0].dispatchEvent(new Event('change', { bubbles: true }));", campo)
            time.sleep(1)
            print(f"Color asignado: {valor_hex}")
            return
        except Exception:
            continue

    print("No se pudo asignar color, se continúa con la prueba.")


def subir_imagen(driver, ruta_imagen):
    """Sube una imagen opcional."""
    print("Subiendo imagen opcional...")

    if not os.path.exists(ruta_imagen):
        raise Exception(f"No existe la imagen de prueba: {ruta_imagen}")

    posibles_xpaths = [
        "//input[@type='file']",
        "//*[@wire:model='foto']",
        "//*[@wire:model.live='foto']",
    ]

    for xpath in posibles_xpaths:
        try:
            input_file = esperar(driver, 3).until(
                EC.presence_of_element_located((By.XPATH, xpath))
            )
            input_file.send_keys(ruta_imagen)
            time.sleep(2)
            print("Imagen cargada correctamente.")
            return
        except Exception:
            continue

    raise NoSuchElementException("No se encontró el input de archivo para la foto.")


def guardar_formulario(driver):
    """Presiona el botón Guardar del formulario."""
    print("Guardando formulario...")

    boton_guardar = esperar(driver).until(
        EC.element_to_be_clickable(
            (By.XPATH, "//button[contains(., 'Guardar')]")
        )
    )
    driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", boton_guardar)
    time.sleep(1)
    driver.execute_script("arguments[0].click();", boton_guardar)
    time.sleep(3)


def detectar_mensaje_exito(driver):
    """Detecta el mensaje de éxito del alta."""
    try:
        html = driver.page_source.lower()
        if "profesor creado correctamente" in html:
            return True
    except Exception:
        pass

    try:
        driver.find_element(By.XPATH, "//*[contains(text(), 'Profesor creado correctamente')]")
        return True
    except Exception:
        return False


# =========================================================
# FLUJO DE LA PRUEBA
# =========================================================
def llenar_formulario_alta_correcta_sin_foto(driver):
    print("Capturando datos del profesor sin foto...")

    seleccionar_usuario(driver)

    escribir_en_campo(driver, "CURP", "AABC900101HGRRRN01")
    escribir_en_campo(driver, "nombre", "CARLOS")
    escribir_en_campo(driver, "apellido_paterno", "NUÑEZ")

    try:
        escribir_en_campo(driver, "apellido_materno", "PEREZ")
    except Exception:
        print("No se encontró apellido_materno, se continúa.")

    try:
        escribir_en_campo(driver, "telefono", "7671234567")
    except Exception:
        print("No se encontró telefono, se continúa.")

    try:
        escribir_en_campo(driver, "perfil", "DOCENTE DE PRUEBA")
    except Exception:
        print("No se encontró perfil, se continúa.")

    asignar_color(driver, "#2563eb")

def llenar_formulario_alta_correcta_con_foto(driver):
    """Llena el formulario con datos válidos y sube imagen."""
    llenar_formulario_alta_correcta_sin_foto(driver)
    subir_imagen(driver, RUTA_IMAGEN_PRUEBA)


def validar_resultado_alta(driver):
    """Valida el resultado del alta."""
    print("Validando resultado del alta...")

    if detectar_mensaje_exito(driver):
        return True, "El sistema creó correctamente al profesor y mostró el mensaje de éxito."

    return False, "No se detectó claramente el mensaje de éxito 'Profesor creado correctamente'."


# =========================================================
# PRUEBA PRINCIPAL
# =========================================================
def prueba_pcb_019_alta_profesor_sin_foto():
    """Ejecuta la prueba principal de alta de profesor sin foto."""
    driver = iniciar_navegador()

    try:
        print("Iniciando prueba PCB-019...")
        hacer_login(driver)
        abrir_modulo_profesores(driver)
        abrir_panel_nuevo_profesor(driver)
        llenar_formulario_alta_correcta_sin_foto(driver)
        guardar_formulario(driver)

        aprobado, detalle = validar_resultado_alta(driver)
        imprimir_resultado(
            "PCB-019 - Alta de profesor",
            aprobado,
            detalle
        )

    except TimeoutException as e:
        imprimir_resultado(
            "PCB-019 - Alta de profesor",
            False,
            f"Tiempo de espera agotado: {str(e)}"
        )

    except NoSuchElementException as e:
        imprimir_resultado(
            "PCB-019 - Alta de profesor",
            False,
            f"No se encontró un elemento esperado: {str(e)}"
        )

    except Exception as e:
        imprimir_resultado(
            "PCB-019 - Alta de profesor",
            False,
            f"Ocurrió una excepción: {str(e)}"
        )

    finally:
        driver.quit()
        print("Navegador cerrado.")


if __name__ == "__main__":
    prueba_pcb_019_alta_profesor_sin_foto()
