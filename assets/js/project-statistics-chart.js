document.addEventListener("DOMContentLoaded", function () {
    fetch("https://app.obbak.es/get_data.php")
    .then(response => response.json())
    .then(data => {
        console.log("Datos recibidos:", data); // 🔍 Verifica en consola

        if (!data || !data.categorias || !Array.isArray(data.categorias) || data.categorias.length === 0) {
            console.warn("⚠️ No hay categorías o datos no válidos.");
            return;
        }

        // Asegurar que el contenedor del gráfico existe en el DOM
        let chartContainer = document.querySelector("#project-statistics-chart");

        if (!chartContainer) {
            console.warn("⚠️ Contenedor no encontrado. Creando div dinámicamente...");
            let cardBody = document.querySelector(".card-body.custom-card-action"); // Ajusta este selector si es necesario
            if (cardBody) {
                chartContainer = document.createElement("div");
                chartContainer.id = "project-statistics-chart";
                cardBody.appendChild(chartContainer);
                console.log("✅ Contenedor creado correctamente.");
            } else {
                console.error("❌ No se encontró el contenedor '.card-body.custom-card-action'.");
                return;
            }
        }

        // Configuración de ApexCharts
        let options = {
            chart: {
                height: 365,
                type: "area",
                toolbar: { show: false }
            },
            xaxis: {
                categories: data.categorias,
                label
