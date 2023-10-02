import { obtenerVehiculos } from "./script.js";
export function initForm() {
  const formularioCrear = document.getElementById("formulario-crear");
  formularioCrear.addEventListener("submit", async (e) => {
    e.preventDefault();

    const nuevoVehiculo = obtenerDatosFormulario();

    try {
      if (nuevoVehiculo) {
        const response = await enviarDatosAlServidor(nuevoVehiculo);

        if (response.valid) {
          obtenerVehiculos();
          limpiarFormulario();
          window.location.reload();
        } else {
          alert(response.message);
        }
      }
    } catch (error) {
      console.error("Error en la solicitud:", error);
    }
  });
}

export async function enviarDatosAlServidor(nuevoVehiculo) {
  try {
    const response = await fetch(
      "http://localhost:8888/prueba-luciano.castro/back/routes/create_vehicle.php",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(nuevoVehiculo),
      }
    );

    if (!response.ok) {
      throw new Error(`Error en la solicitud: ${response.status}`);
    }

    const data = await response.json();

    return {
      valid: !data.error,
      message: data.message,
    };
  } catch (error) {
    console.error("Error en la solicitud:", error);

    return {
      valid: false,
      message: "La MATRICULA o el LUGAR estan en uso...",
    };
  }
}

export function limpiarFormulario() {
  const inputs = document.querySelectorAll("input");
  inputs.forEach((input) => (input.value = ""));
}

function obtenerDatosFormulario() {
  const matriculaInput = document.getElementById("matricula");
  const marcaInput = document.getElementById("marca");
  const modeloInput = document.getElementById("modelo");
  const colorInput = document.getElementById("color");
  const lugarInput = document.getElementById("lugar");

  const matricula = matriculaInput.value;
  const marca = marcaInput.value;
  const modelo = modeloInput.value;
  const color = colorInput.value;
  const lugar = parseInt(lugarInput.value);

  if (lugar < 1 || lugar > 50) {
    alert("El número de lugar debe estar entre 1 y 50.");
    return null;
  }

  const matriculaPattern = /^[A-Z]{3}\d{4}$/;
  if (!matriculaPattern.test(matricula)) {
    alert(
      "La matrícula debe tener 3 letras seguidas de 4 números (por ejemplo, ABC1234)."
    );
    return null;
  }

  return {
    matricula,
    marca,
    modelo,
    color,
    lugar,
  };
}
