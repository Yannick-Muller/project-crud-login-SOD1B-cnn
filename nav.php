<php 

if (isset($_SESSION["loginType"]) && $_SESSION["loginType"] === "admin") {
    include "nav-admin.html";
} else if (isset($_SESSION["loginType"]) && $_SESSION["loginType"] === "client") {
    include "nav-client.html";
} else {
    include "nav-html";
}
    ?></php>