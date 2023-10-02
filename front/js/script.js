import { initForm } from "./formulario.js";
import { mostrarVehiculosEnEdificio } from "./edificio.js";

document.addEventListener("DOMContentLoaded", () => {
  initForm();
  obtenerVehiculos();
});

export async function obtenerVehiculos() {
  try {
    const response = await fetch(
      "http://localhost:8888/prueba-luciano.castro/back/routes/list_vehicles.php",
      {
        method: "GET",
      }
    );

    if (!response.ok) {
      throw new Error(`Error en la solicitud: ${response.status}`);
    }

    const data = await response.json();
    const listaVehiculos = document.getElementById("lista-vehiculos");

    listaVehiculos.innerHTML = "";

    if (data.length === 0) {
      const mensajeVacio = document.createElement("p");
      mensajeVacio.textContent =
        "El estacionamiento está vacío, ya puede registar su vehciulo...";
      listaVehiculos.appendChild(mensajeVacio);
    } else {
      mostrarVehiculosEnEdificio(data);
    }
  } catch (error) {
    console.error("Error en la solicitud:", error);
  }
  console.log("obteniendo vehiculos");
}
