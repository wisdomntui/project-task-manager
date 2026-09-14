import $ from "jquery";
import * as bootstrap from "bootstrap";

window.$ = $;
window.jQuery = $;
window.bootstrap = bootstrap;

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        Accept: "application/json",
    },
});

import "./api/tasks";
