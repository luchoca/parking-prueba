import { obtenerVehiculos } from "./script.js";

export function mostrarVehiculosEnEdificio(vehiculos) {
  const edificio = document.getElementById("edificio");
  edificio.innerHTML = "";
  const lugaresOcupados = edificio.querySelectorAll(".ocupado");
  lugaresOcupados.forEach((lugarDiv) => {
    lugarDiv.innerHTML = "";
    lugarDiv.classList.remove("ocupado");
  });

  const edificioMatrix = Array.from({ length: 5 }, () => Array(10).fill(null));

  vehiculos.forEach((vehiculo) => {
    const lugar = vehiculo.lugar;
    if (lugar >= 1 && lugar <= 50) {
      const piso = Math.floor((lugar - 1) / 10);
      const lugarEnPiso = (lugar - 1) % 10;
      edificioMatrix[piso][lugarEnPiso] = vehiculo;
    }
  });

  for (let i = 0; i < 5; i++) {
    const pisoDiv = document.createElement("div");
    pisoDiv.classList.add("piso");
    for (let j = 0; j < 10; j++) {
      const lugarDiv = document.createElement("div");
      lugarDiv.classList.add("lugar");
      const vehiculo = edificioMatrix[i][j];
      if (vehiculo) {
        lugarDiv.textContent = `${vehiculo.matricula}`;
        lugarDiv.classList.add("ocupado");

        const desocuparBtn = document.createElement("button");
        desocuparBtn.textContent = "Desocupar";
        desocuparBtn.addEventListener("click", () => {
          desocuparLugar(vehiculo);
        });
        lugarDiv.appendChild(desocuparBtn);
      } else {
        lugarDiv.textContent = `Lugar ${i * 10 + j + 1}`;
        lugarDiv.classList.add("libre");
      }
      pisoDiv.appendChild(lugarDiv);
    }
    edificio.appendChild(pisoDiv);
  }

  const vehiculosActuales = vehiculos.length;
  const mensajeLleno = document.getElementById("mensaje-lleno");
  mensajeLleno.style.display = vehiculosActuales >= 50 ? "block" : "none";
}

export function desocuparLugar(vehiculo) {
  fetch(
    `http://localhost:8888/prueba-luciano.castro/back/routes/delete_vehicle.php?id=${vehiculo.id}`,
    {
      method: "DELETE",
    }
  )
    .then((response) => {
      if (response.status === 204) {
        alert("El vehículo se eliminó correctamente.");
        obtenerVehiculos();
        window.location.reload();
      } else {
        alert("Error al eliminar el vehículo.");
      }
    })
    .catch((error) => {
      console.error("Hubo un error al realizar la solicitud:", error);
    });
  console.log(`Eliminando vehículo con ID: ${vehiculo.id}`);
}
