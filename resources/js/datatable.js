import { DataTable } from "simple-datatables";
import "simple-datatables/dist/style.css"; // Pastikan CSS ikut dimuat

window.addEventListener("DOMContentLoaded", () => {
    const datatablesSimple = document.getElementById("datatablesSimple");
    if (datatablesSimple) {
        new DataTable(datatablesSimple);
        console.log("Simple-DataTables berhasil dimuat!");
    } else {
        console.log("Element dengan ID 'datatablesSimple' tidak ditemukan.");
    }
});
