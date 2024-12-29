document.addEventListener("DOMContentLoaded", function () {
  const navbarToggle = document.getElementById("navbar-toggle");
  const navbar = document.querySelector(".navbar");

  navbarToggle.addEventListener("click", () => {
    navbar.classList.toggle("active");
  });

  const simbolsCripto = [
    "BTCUSDT",
    "ETHUSDT",
    "BNBUSDT",
    "SOLUSDT",
    "ADAUSDT",
    "DOGEUSDT",
    "AVAXUSDT",
    "SHIBUSDT",
    "PEPEUSDT",
    "DOTUSDT",
    "XRPUSDT",
    "PENGUUSDT",
    "LTCUSDT",
    "LINKUSDT",
    "XLMUSDT",
  ];

  const cripto = document.getElementById("cripto");

  simbolsCripto.forEach((element) => {
    const optons = document.createElement("option");

    optons.value = element;
    optons.textContent = element.replace("USDT", "-USDT");
    cripto.appendChild(optons);
  });
});
