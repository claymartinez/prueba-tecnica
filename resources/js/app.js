import "bootstrap/dist/css/bootstrap.min.css";
import "@fortawesome/fontawesome-free/css/all.min.css";
import "bootstrap";
import "./bootstrap";
import $ from "jquery";
import "jquery-validation";

// ...existing code...
$(function () {
    // Método: solo letras y espacios (sin tildes)
    $.validator.addMethod(
        "letrasEspacios",
        function (value, element) {
            return this.optional(element) || /^[A-Za-z\s]+$/.test(value);
        },
        "Solo letras y espacios."
    );

    // Método: email simple por regex
    $.validator.addMethod(
        "emailRegex",
        function (value, element) {
            return (
                this.optional(element) ||
                /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)
            );
        },
        "Correo inválido."
    );

    const $form = $("#formEmpleado");
    if ($form.length) {
        $form.validate({
            ignore: [],
            rules: {
                nombre: { required: true, minlength: 3, letrasEspacios: true },
                email: { required: true, maxlength: 255, emailRegex: true },
                sexo: { required: true },
                area_id: { required: true },
                descripcion: { required: true, minlength: 10 },
                "roles[]": { required: true },
            },
            messages: {
                nombre: { required: "El nombre es obligatorio." },
                email: { required: "El correo es obligatorio." },
                sexo: { required: "Selecciona el sexo." },
                area_id: { required: "Selecciona un área." },
                descripcion: { required: "La descripción es obligatoria." },
                "roles[]": { required: "Selecciona al menos un rol." },
            },
            errorElement: "div",
            errorClass: "invalid-feedback",
            highlight(element) {
                $(element).addClass("is-invalid");
            },
            unhighlight(element) {
                $(element).removeClass("is-invalid");
            },
            errorPlacement(error, element) {
                if (element.is(":radio")) {
                    error.addClass("d-block");
                    element.closest(".mb-3").append(error);
                } else if (element.attr("name") === "roles[]") {
                    error.addClass("d-block");
                    $("#rolesGroup").append(error);
                } else if (element.hasClass("form-check-input")) {
                    error.addClass("d-block");
                    element.closest(".form-check").append(error);
                } else {
                    element.after(error);
                }
            },
            submitHandler(form) {
                const $btn = $(form).find('button[type="submit"]');
                if (!$btn.find(".fa-spinner").length) {
                    $btn.prop("disabled", true).prepend(
                        '<i class="fa fa-spinner fa-spin me-1"></i>'
                    );
                }
                form.submit();
            },
        });
    }
});
