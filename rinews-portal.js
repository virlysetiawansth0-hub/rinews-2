(function () {
  // 0. Hamburger Navigation Menu Toggle
  var navbar = document.getElementById("rn-main-navbar");
  var navToggle = navbar ? navbar.querySelector(".js-rn-nav-toggle") : null;
  var navDrawer = document.getElementById("rn-nav-drawer");
  var navClose = navbar ? navbar.querySelector(".js-rn-nav-close") : null;

  function setMenuOpen(open) {
    if (!navbar || !navToggle) return;
    navbar.classList.toggle("is-menu-open", open);
    navToggle.setAttribute("aria-expanded", open ? "true" : "false");
    if (navDrawer) {
      navDrawer.setAttribute("aria-hidden", open ? "false" : "true");
    }
  }

  if (navToggle) {
    navToggle.addEventListener("click", function (e) {
      e.stopPropagation();
      setMenuOpen(!navbar.classList.contains("is-menu-open"));
    });
  }
  if (navClose) {
    navClose.addEventListener("click", function () {
      setMenuOpen(false);
    });
  }
  if (navbar) {
    document.addEventListener("click", function (e) {
      if (navbar.classList.contains("is-menu-open") && !navbar.contains(e.target)) {
        setMenuOpen(false);
      }
    });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && navbar.classList.contains("is-menu-open")) {
        setMenuOpen(false);
      }
    });
  }

  // 0b. Theme Color Mode Switcher (Dark Default)
  var themeToggleBtn = document.querySelector(".js-rn-theme-toggle");
  function applyPortalTheme(mode) {
    var normalized = mode === "light" ? "light" : "dark";
    document.documentElement.setAttribute("data-rn-theme", normalized);
    if (document.body) {
      document.body.classList.toggle("rn-theme-light", normalized === "light");
      document.body.classList.toggle("rn-theme-dark", normalized === "dark");
    }
    if (themeToggleBtn) {
      themeToggleBtn.textContent = normalized === "light" ? "Mode: Terang" : "Mode: Gelap";
    }
  }
  try {
    var savedMode = window.localStorage.getItem("rinews_theme_mode");
    if (savedMode === "dark" || savedMode === "light") {
      applyPortalTheme(savedMode);
    }
  } catch (err) {}
  if (themeToggleBtn) {
    themeToggleBtn.addEventListener("click", function () {
      var cur = document.documentElement.getAttribute("data-rn-theme") === "light" ? "light" : "dark";
      var next = cur === "light" ? "dark" : "light";
      applyPortalTheme(next);
      try {
        window.localStorage.setItem("rinews_theme_mode", next);
      } catch (err) {}
    });
  }

  // 1. Slide Berita Baru (6 slides auto-play + thumb switcher)
  document.querySelectorAll(".js-rn-slider").forEach(function (slider) {
    var slides = slider.querySelectorAll(".rn-slide-item");
    var thumbs = slider.querySelectorAll(".rn-slide-thumb-btn");
    var prevBtn = slider.querySelector(".js-rn-prev");
    var nextBtn = slider.querySelector(".js-rn-next");
    if (!slides.length) return;

    var idx = 0;
    function showSlide(nextIdx) {
      idx = (nextIdx + slides.length) % slides.length;
      slides.forEach(function (el, i) {
        el.classList.toggle("is-active", i === idx);
      });
      thumbs.forEach(function (el, i) {
        el.classList.toggle("is-active", i === idx);
      });
    }

    thumbs.forEach(function (btn) {
      btn.addEventListener("click", function () {
        var target = parseInt(btn.getAttribute("data-slide-index") || "0", 10);
        showSlide(target);
      });
    });

    if (prevBtn) prevBtn.addEventListener("click", function () { showSlide(idx - 1); });
    if (nextBtn) nextBtn.addEventListener("click", function () { showSlide(idx + 1); });

    setInterval(function () {
      showSlide(idx + 1);
    }, 5500);
  });

  // 2. Copy Link Media Share
  document.querySelectorAll(".js-rn-copy-link").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var url = btn.getAttribute("data-copy-url") || window.location.href;
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).catch(function () {});
      }
      var label = btn.querySelector(".js-rn-copy-label");
      if (label) {
        var old = label.textContent;
        label.textContent = "Tersalin!";
        setTimeout(function () {
          label.textContent = old;
        }, 2000);
      } else {
        btn.style.backgroundColor = "#059669";
        setTimeout(function () {
          btn.style.backgroundColor = "";
        }, 1800);
      }
    });
  });

  // 3. YouTube Modal Player
  var ytModal = document.getElementById("rn-yt-modal");
  var ytIframe = document.getElementById("rn-yt-iframe");
  var ytTitle = document.getElementById("rn-yt-modal-title");
  var ytClose = document.getElementById("rn-yt-modal-close");

  document.querySelectorAll(".js-rn-play-yt").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var vid = btn.getAttribute("data-yt-id");
      var title = btn.getAttribute("data-yt-title") || "Video Berita";
      if (!vid || !ytModal || !ytIframe) return;
      if (ytTitle) ytTitle.textContent = title;
      ytIframe.src = "https://www.youtube.com/embed/" + encodeURIComponent(vid) + "?autoplay=1&rel=0";
      ytModal.style.display = "flex";
    });
  });

  function closeYtModal() {
    if (!ytModal || !ytIframe) return;
    ytIframe.src = "";
    ytModal.style.display = "none";
  }

  if (ytClose) ytClose.addEventListener("click", closeYtModal);
  if (ytModal) {
    ytModal.addEventListener("click", function (e) {
      if (e.target === ytModal) closeYtModal();
    });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && ytModal.style.display === "flex") {
        closeYtModal();
      }
    });
  }

  // 4. TikTok Modal Player (Direct Official Player Embed)
  var ttModal = document.getElementById("rn-tt-modal");
  var ttIframe = document.getElementById("rn-tt-iframe");
  var ttTitle = document.getElementById("rn-tt-modal-title");
  var ttClose = document.getElementById("rn-tt-modal-close");
  var ttExt = document.getElementById("rn-tt-external-link");

  document.querySelectorAll(".js-rn-play-tt").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var vid = btn.getAttribute("data-tt-id");
      var url = btn.getAttribute("data-tt-url") || "https://www.tiktok.com/@ri.news.portal";
      var title = btn.getAttribute("data-tt-title") || "TikTok Video RINEWS.ID";
      if (!vid || !ttModal || !ttIframe) return;
      if (ttTitle) ttTitle.textContent = title;
      if (ttExt) ttExt.href = url;
      ttIframe.src = "https://www.tiktok.com/player/v1/" + encodeURIComponent(vid) + "?music_info=1&description=1&autoplay=1";
      ttModal.style.display = "flex";
    });
  });

  function closeTtModal() {
    if (!ttModal || !ttIframe) return;
    ttIframe.src = "";
    ttModal.style.display = "none";
  }

  if (ttClose) ttClose.addEventListener("click", closeTtModal);
  if (ttModal) {
    ttModal.addEventListener("click", function (e) {
      if (e.target === ttModal) closeTtModal();
    });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && ttModal.style.display === "flex") {
        closeTtModal();
      }
    });
  }
})();
