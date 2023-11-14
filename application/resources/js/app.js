import "./bootstrap";
import "./fluent";

if (window.versions?.electron()) {
  window.addEventListener("beforeunload", () => {
    document.body.style.display = "none";
  });
}
