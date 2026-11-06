"use strict";
let Content = function() {
    var e = { linkClass: "side-link", nilaiPost: "a", mouseRequestTime: 5e4, skipUrl: ["proses"], contentTampil: "waitingDone", requestTimeout: 3e4, linkClicked: "clickingLink", cache: null, showLoading: !0, loadingId: "preloader" },
        a = { aa: !1, ab: !1, ac: !1, ad: null, ae: null, af: !1, ag: null, ai: null, aj: null, ak: null, al: null, am: null, an: null, ao: null, ap: null, aq: null },
        t = (t, n) => {
            if (void 0 != g.null && delete g.null, void 0 !== g[t]) {
                document.getElementById("__SND__").innerHTML = g[t].i, a.ad = document.getElementById("__SND__").children, u.ga();
                let l = null;
                c(t), (l = $("#__SND__ ." + e.linkClass)).length > 0 && m(l), !0 == n && window.history.pushState({ a: t }, "", t), void 0 !== g[t].t && (document.title = g[t].t), !0 == a.af && (a.af = !1), "function" == typeof window[e.contentTampil] && (0, window[e.contentTampil])(!0), "object" == typeof a.ak && b(), null !== a.ap && void 0 !== a.ap && !0 === e.showLoading && (a.ap.style.display = "none")
            } else !1 === a.aa && setTimeout(() => { a.aa = !0, o(t) }, 50)
        },
        n = (e, t) => { v(t); let n;!0 === a.al ? (null == a.ak && (a.ak = []), a.ak.push(e), b()) : e(n = document.getElementById("__SND__").children) },
        l = () => { setTimeout(() => { if (!1 == a.aa && !1 == a.ab && !1 == a.af && !1 == a.ac) { a.ae = null, a.ag = null;!1 === ("boolean" == typeof e.cache && e.cache) && (g = {}), null !== a.aj && (clearTimeout(a.aj), a.aj = null) } l() }, 5e3) },
        i = n => { n.preventDefault(); let l = window.location.href;!0 === ("boolean" != typeof e.cache || e.cache) && void 0 !== g[l] ? t(l) : (a.aa = !0, o(l, !1)) },
        r = () => { setTimeout(() => { a.ab || null == a.ag || !1 != a.aa || (clearTimeout(a.ag), a.af = !1) }, 250), !0 === a.ab && (a.ab = !1, a.ac = !1) },
        u = {
            ga() {
                let e = document.getElementById("__SND__"),
                    t = e.querySelectorAll("script");
                if (t.length > 0)
                    for (let n = 0; n < t.length; n++) {
                        let l = null,
                            i = null,
                            r = null,
                            o = null,
                            s = null,
                            g = null;
                        void 0 !== (l = t[n].getAttribute("tobody")) && null !== l ? (t[n].removeAttribute("tobody"), null !== l && l.length > 0 ? 0 == l.indexOf("before;") ? u.gb(t[n], l, 7, "before;") : 0 == l.indexOf("after;") ? u.gb(t[n], l, 6, "after;") : 0 == l.indexOf("top;") ? u.gb(t[n], l, 4, "top;") : 0 == l.indexOf("bottom;") ? u.gb(t[n], l, 7, "bottom;") : 0 == l.indexOf("sesudah;") ? u.gb(t[n], l, 8, "sesudah;") : 0 == l.indexOf("sebelum;") ? u.gb(t[n], l, 8, "sebelum;") : 0 == l.indexOf("awal;") ? u.gb(t[n], l, 5, "awal;") : console.error('nilai "tobody" tidak sesuai. "[after;|before;|top;|bottom;|sesudah;|sebelum;|awal;][src;script_src|id_element]"', t[n]) : void 0 !== a.ao[t[n].tagName] && (void 0 !== (s = t[n].getAttribute("src")) && (g = s, t[n].removeAttribute("src")), null === g || a.ao[t[n].tagName].src.includes(g) ? t[n].innerHTML.length > 0 && !a.ao[t[n].tagName].inner.includes(t[n].innerHTML) && (t[n].attributes.length > 0 && (r = u.gc(t[n].attributes)), o = t[n].innerHTML, y(e, i = Main.buat.script(o, g, r)), a.ao[t[n].tagName].inner.push(t[n].innerHTML), t[n].remove()) : (t[n].attributes.length > 0 && (r = u.gc(t[n].attributes)), o = t[n].innerHTML, y(e, i = Main.buat.script(o, g, r)), a.ao[t[n].tagName].src.push(g), t[n].remove()))) : (void 0 !== (s = t[n].getAttribute("src")) && (g = s, t[n].removeAttribute("src")), t[n].attributes.length > 0 && (r = u.gc(t[n].attributes)), o = t[n].innerHTML, i = Main.buat.script(o, g, r), t[n].parentNode.replaceChild(i, t[n]))
                    }
                e = null, t = null, u.gd()
            },
            gb(e, t, n, l) {
                let i = null,
                    r = null,
                    o = null,
                    s = null,
                    g = null,
                    c = null,
                    d = null;
                if (void 0 !== (c = e.getAttribute("src")) ? e.removeAttribute("src") : c = null, s = e.innerHTML, void 0 == a.ao[e.tagName] && (a.ao[e.tagName] = [], a.ao[e.tagName].src = [], a.ao[e.tagName].inner = []), o = u.gc(e.attributes), g = Main.buat.script(s, c, o), t.indexOf("src;") == n) {
                    switch ((i = t.split(";"))[0]) {
                        case "top":
                        case "before":
                        case "sebelum":
                            null === c || "" === c || a.ao[e.tagName].src.includes(c) || (r = $('script[src="' + i[2] + '"]'), a.ao[e.tagName].src.push(c), y(r[0], g)), null === s || "" === s || a.ao[e.tagName].inner.includes(s) || (r = $('script[src="' + i[2] + '"]'), a.ao[e.tagName].inner.push(s), y(r[0], g));
                            break;
                        case "bottom":
                        case "after":
                        case "sesudah":
                            null === c || "" === c || a.ao[e.tagName].src.includes(c) || (r = $('script[src="' + i[2] + '"]'), a.ao[e.tagName].src.push(c), y(r[0], g, !0)), null === s || "" === s || a.ao[e.tagName].inner.includes(s) || (r = $('script[src="' + i[2] + '"]'), a.ao[e.tagName].inner.push(s), y(r[0], g, !0))
                    }
                    e.remove()
                } else if (t.indexOf("#") == n) {
                    switch ((i = t.split(";"))[0]) {
                        case "top":
                        case "before":
                        case "sebelum":
                            null === c || "" === c || a.ao[e.tagName].src.includes(c) || (r = $(i[1]), a.ao[e.tagName].src.push(c), y(r[0], g)), null === s || "" === s || a.ao[e.tagName].inner.includes(s) || (r = $(i[1]), a.ao[e.tagName].inner.push(s), y(r[0], g));
                            break;
                        case "bottom":
                        case "after":
                        case "sesudah":
                            null === c || "" === c || a.ao[e.tagName].src.includes(c) || (r = $(i[1]), a.ao[e.tagName].src.push(c), y(r[0], g, !0)), null === s || "" === s || a.ao[e.tagName].inner.includes(s) || (r = $(i[1]), a.ao[e.tagName].inner.push(s), y(r[0], g, !0))
                    }
                    e.remove()
                } else 0 == t.indexOf("awal;") ? (r = document.querySelector("body"), null === c || "" === c || a.ao[e.tagName].src.includes(c) || (a.ao[e.tagName].src.push(c), r.insertBefore(g, r.firstChild)), null === s || "" === s || a.ao[e.tagName].inner.includes(s) || (a.ao[e.tagName].inner.push(s), r.insertBefore(g, r.firstChild)), e.remove()) : console.error('nilai setelah "' + l + '" tidak sesuai. "[src;script_src|id_element]"', e);
                i = null, r = null, o = null, s = null, g = null, c = null, d = null
            },
            gc(e) {
                let a = {};
                for (let t = 0; t < e.length; t++) {
                    let n = e.item(t);
                    a[n.nodeName] = n.nodeValue
                }
                return a
            },
            gd() {
                let e = document.getElementById("__SND__"),
                    t = e.querySelectorAll("link");
                if (t.length > 0)
                    for (let n = 0; n < t.length; n++) {
                        let l = null,
                            i = null,
                            r = null,
                            o = null,
                            s = null,
                            g = null;
                        void 0 !== (l = t[n].getAttribute("tobody")) && null !== l ? (t[n].removeAttribute("tobody"), null !== l && l.length > 0 ? 0 == l.indexOf("before;") ? u.ge(t[n], l, 7, "before;") : 0 == l.indexOf("after;") ? u.ge(t[n], l, 6, "after;") : 0 == l.indexOf("top;") ? u.ge(t[n], l, 4, "top;") : 0 == l.indexOf("bottom;") ? u.ge(t[n], l, 7, "bottom;") : 0 == l.indexOf("sesudah;") ? u.ge(t[n], l, 8, "sesudah;") : 0 == l.indexOf("sebelum;") ? u.ge(t[n], l, 8, "sebelum;") : 0 == l.indexOf("awal;") ? u.ge(t[n], l, 5, "awal;") : console.error('nilai "tobody" tidak sesuai. "[after;|before;|top;|bottom;|sesudah;|sebelum;|awal;][href;link_href|id_element]"', t[n]) : void 0 !== a.ao[t[n].tagName] && (void 0 !== (s = t[n].getAttribute("href")) && (g = s, t[n].removeAttribute("href")), null === g || a.ao[t[n].tagName].href.includes(g) ? t[n].innerHTML.length > 0 && !a.ao[t[n].tagName].inner.includes(t[n].innerHTML) && (t[n].attributes.length > 0 && (r = u.gc(t[n].attributes)), o = t[n].innerHTML, y(e, i = Main.buat.link(o, g, r)), a.ao[t[n].tagName].inner.push(t[n].innerHTML), t[n].remove()) : (t[n].attributes.length > 0 && (r = u.gc(t[n].attributes)), o = t[n].innerHTML, y(e, i = Main.buat.link(o, g, r)), a.ao[t[n].tagName].href.push(g), t[n].remove()))) : (void 0 !== l && t[n].removeAttribute("tobody"), void 0 !== (s = t[n].getAttribute("href")) && (g = s, t[n].removeAttribute("href")), t[n].attributes.length > 0 && (r = u.gc(t[n].attributes)), o = t[n].innerHTML, i = Main.buat.link(o, g, r), t[n].parentNode.replaceChild(i, t[n]))
                    }
                e = null, t = null
            },
            ge(e, t, n, l) {
                let i = null,
                    r = null,
                    o = null,
                    s = null,
                    g = null,
                    c = null,
                    d = null;
                if (void 0 !== (c = e.getAttribute("href")) ? e.removeAttribute("href") : c = null, s = e.innerHTML, void 0 == a.ao[e.tagName] && (a.ao[e.tagName] = [], a.ao[e.tagName].href = [], a.ao[e.tagName].inner = []), o = u.gc(e.attributes), g = Main.buat.link(s, c, o), t.indexOf("href;") == n) {
                    switch ((i = t.split(";"))[0]) {
                        case "top":
                        case "before":
                        case "sebelum":
                            null === c || "" === c || a.ao[e.tagName].href.includes(c) || (r = $('link[href="' + i[2] + '"]'), a.ao[e.tagName].href.push(c), y(r[0], g)), null === s || "" === s || a.ao[e.tagName].inner.includes(s) || (r = $('link[href="' + i[2] + '"]'), a.ao[e.tagName].inner.push(s), y(r[0], g));
                            break;
                        case "bottom":
                        case "after":
                        case "sesudah":
                            null === c || "" === c || a.ao[e.tagName].href.includes(c) || (r = $('link[href="' + i[2] + '"]'), a.ao[e.tagName].href.push(c), y(r[0], g, !0)), null === s || "" === s || a.ao[e.tagName].inner.includes(s) || (r = $('link[href="' + i[2] + '"]'), a.ao[e.tagName].inner.push(s), y(r[0], g, !0))
                    }
                    e.remove()
                } else if (t.indexOf("#") == n) {
                    switch ((i = t.split(";"))[0]) {
                        case "top":
                        case "before":
                        case "sebelum":
                            null === c || "" === c || a.ao[e.tagName].href.includes(c) || (r = $(i[1]), a.ao[e.tagName].href.push(c), y(r[0], g)), null === s || "" === s || a.ao[e.tagName].inner.includes(s) || (r = $(i[1]), a.ao[e.tagName].inner.push(s), y(r[0], g));
                            break;
                        case "bottom":
                        case "after":
                        case "sesudah":
                            null === c || "" === c || a.ao[e.tagName].href.includes(c) || (r = $(i[1]), a.ao[e.tagName].href.push(c), y(r[0], g, !0)), null === s || "" === s || a.ao[e.tagName].inner.includes(s) || (r = $(i[1]), a.ao[e.tagName].inner.push(s), y(r[0], g, !0))
                    }
                    e.remove()
                } else 0 == t.indexOf("awal;") ? (r = document.querySelector("body"), null === c || "" === c || a.ao[e.tagName].href.includes(c) || (a.ao[e.tagName].href.push(c), r.insertBefore(g, r.firstChild)), null === s || "" === s || a.ao[e.tagName].inner.includes(s) || (a.ao[e.tagName].inner.push(s), r.insertBefore(g, r.firstChild)), e.remove()) : console.error('nilai setelah "' + l + '" tidak sesuai. "[href;link_href|id_element]"', e);
                i = null, r = null, o = null, s = null, g = null, c = null, d = null
            }
        },
        o = (n, l = !0) => {
            if (null === a.al && (a.al = !0), a.ak = [], a.am = [], a.aq = 0, !0 === a.aa) {
                a.ae = n;
                let i = {};
                i._SND_PAGE_ = e.nilaiPost, $.ajax({ url: n, type: "post", data: i, timeout: e.requestTimeout, enctype: "mutipart/form-data", beforeSend: function() {}, success: function(e) { a.aa = !1, "string" == typeof e && (e = JSON.parse(e)), "object" == typeof e && (!0 == e.h ? (g[n] = {}, g[n].i = e.i, g[n].t = e.t, !0 == a.af && t(n, !0), !1 === l && t(n)) : "p" == e.h ? window.location.href = e.i : "r" == e.h ? window.location.href = window.location.href : Main.pesan(e.i, e.t)) }, error: function(e) { a.aa = !1, "0" == e.status && g[n] && t(n, !0) }, complete: function() { a.aa = !1 }, cache: "boolean" == typeof e.cache && e.cache })
            }
        },
        s = (n, l, i) => {
            if (l.preventDefault(), "function" == typeof window[e.linkClicked] && !1 === (0, window[e.linkClicked])(l, i)) return !1;
            n === a.ae ? !0 === a.aa ? (null !== a.ap && void 0 !== a.ap && e.showLoading && (a.ap.style.display = "block"), h(n)) : (a.af = !0, t(n, !0)) : (a.aa = !0, a.af = !0, null !== a.ap && void 0 !== a.ap && e.showLoading && (a.ap.style.display = "block"), o(n))
        },
        g = {},
        c = a => {
            let t = document.querySelectorAll('.' + e.linkClass);
            t.length > 0 && t.forEach((e, t) => {
                let n = e.getAttribute("href");
                e.classList.contains("active") && n != a && e.classList.remove("active"), a.includes(n) && e.classList.add("active")
            })
        },
        d = t => {!1 === a.ab && (a.ab = !0, null !== a.ag && clearTimeout(a.ag), a.ag = setTimeout(() => {!1 === a.aa && !1 === a.af && !1 === a.ac ? (a.aa = !0, a.ac = !0, o(t)) : clearTimeout(a.ag) }, e.mouseRequestTime)) },
        f = () => {
            if (Main.adakah(e.linkClass)) {
                let a = $("." + e.linkClass);
                a.length > 0 && m(a)
            }
            if (!0 === e.cache) {
                let t = $(".side-link.active");
                if (1 == t.length) {
                    let n = t.attr("href");
                    if (void 0 == g[n]) {
                        let l = document.getElementById("__SND__"),
                            i = document.title;
                        g[n] = {}, g[n].i = l.innerHTML, g[n].t = i
                    }
                }
            }
        },
        m = a => {
            console.log("linked link"), a.each((a, t) => {
                let n = t.getAttribute("href"),
                    l = e.skipUrl.some(e => n.includes(e));
                null === n || n.includes("#") || l || n.includes("void") || (t.addEventListener("click", e => { s(n, e, t) }), t.addEventListener("mouseover", e => { d(n) }), t.addEventListener("mouseleave", e => { r() }))
            })
        },
        h = e => { null !== a.aj && clearTimeout(a.aj), a.aj = setTimeout(() => {!1 === a.aa ? (a.af = !0, t(e, !0)) : h(e) }, 100) },
        b = () => {
            if ("object" == typeof a.am && null !== a.am) {
                if (a.am.length > 0) k(0);
                else
                    for (; a.aq < a.ak.length; a.aq++)(0, a.ak[a.aq])(a.ad)
            } else setTimeout(() => { b() }, 200)
        },
        p = (e, a) => {
            switch (a) {
                case "a":
                    throw Error("parameter Content.siap harus berupa fungsi. tipe '" + typeof e + "' yang dimasukan");
                case "b":
                    throw Error("parameter Content.tungguAda harus berupa array | object | string. null yang dimasukan");
                case "c":
                    throw Error("nilai Content.tungguAda harus berupa array | object | string. null yang dimasukan");
                case "d":
                    console.error(e, " yang ditunggu, tidak ada");
                    break;
                case "e":
                    console.error(e, " tidak diperbolehkan dalam tungguAda")
            }
        },
        N = (t = "__SND__", n = !0) => {
            let l = $("*[tobody]"),
                r = null,
                o = null,
                s = null,
                g = null,
                c = null,
                d = document.getElementById(t),
                f = null;
            null == a.ao && (a.ao = []), null === a.ap && e.showLoading && (a.ap = document.getElementById(e.loadingId)), l.each((e, t) => { r = t.getAttribute("tobody"), t.removeAttribute("tobody"), void 0 === a.ao[t.tagName] && (a.ao[t.tagName] = [], "SCRIPT" == t.tagName ? a.ao[t.tagName].src = [] : "LINK" == t.tagName && (a.ao[t.tagName].href = []), a.ao[t.tagName].inner = []), null !== r && "SCRIPT" == t.tagName ? 0 == r.indexOf("before;") ? u.gb(t, r, 7, "before;") : 0 == r.indexOf("after;") ? u.gb(t, r, 6, "after;") : 0 == r.indexOf("top;") ? u.gb(t, r, 4, "top;") : 0 == r.indexOf("bottom;") ? u.gb(t, r, 7, "bottom;") : 0 == r.indexOf("sesudah;") ? u.gb(t, r, 8, "sesudah;") : 0 == r.indexOf("sebelum;") ? u.gb(t, r, 8, "sebelum;") : 0 == r.indexOf("awal;") ? u.gb(t, r, 5, "awal;") : (o = t.getAttribute("src"), f = !1, o.length > 0 && !a.ao[t.tagName].src.includes(o) ? f = !0 : t.innerHTML.length > 0 && !a.ao[t.tagName].inner.includes(t.innerHTML) && (f = !1), t.attributes.length > 0 && (s = u.gc(t.attributes)), g = t.innerHTML, y(d, c = Main.buat.script(g, o, s)), !0 === f ? a.ao[t.tagName].src.push(o) : a.ao[t.tagName].inner.push(g), t.remove()) : null !== r && "LINK" == t.tagName && (0 == r.indexOf("before;") ? u.ge(t, r, 7, "before;") : 0 == r.indexOf("after;") ? u.ge(t, r, 6, "after;") : 0 == r.indexOf("top;") ? u.ge(t, r, 4, "top;") : 0 == r.indexOf("bottom;") ? u.ge(t, r, 7, "bottom;") : 0 == r.indexOf("sesudah;") ? u.ge(t, r, 8, "sesudah;") : 0 == r.indexOf("sebelum;") ? u.ge(t, r, 8, "sebelum;") : 0 == r.indexOf("awal;") ? u.ge(t, r, 5, "awal;") : (o = t.getAttribute("href"), f = !1, o.length > 0 && !a.ao[t.tagName].href.includes(o) ? f = !0 : t.innerHTML.length > 0 && !a.ao[t.tagName].inner.includes(t.innerHTML) && (f = !1), t.attributes.length > 0 && (s = u.gc(t.attributes)), g = t.innerHTML, y(d, c = Main.buat.link(g, o, s)), !0 === f ? a.ao[t.tagName].href.push(o) : a.ao[t.tagName].inner.push(g), t.remove())) }), l = null, r = null, o = null, s = null, g = null, c = null, d = null, f = null, !0 === n && window.addEventListener("popstate", e => { i(e) })
        },
        k = e => { setTimeout(() => { e < a.am.length ? a.an > 0 ? !1 == ("function" == typeof window[a.am[e]] || "function" == typeof jQuery.fn[a.am[e]]) ? (a.an--, k(e)) : (a.an = 100, k(e + 1)) : (a.an = 100, p(a.am[e], "d"), k(e + 1)) : (a.am = [], a.an = 100, b()) }, 100) },
        v = e => {
            if (null === a.an && (a.an = 100), ("object" == typeof e || "string" == typeof e) && e.length > 0) switch (typeof e) {
                case "object":
                    e.forEach((e, t) => { null !== e ? "object" == typeof e || "array" == typeof e ? v(e) : "string" == typeof e ? null === a.am || a.am.includes(e) || a.am.push(e) : p(e, "e") : p(e, "c") });
                    break;
                case "string":
                    a.am.includes(e) || a.am.push(e);
                    break;
                default:
                    p(e, "b")
            }
        },
        y = (e, a, t = !1) => {!0 === t ? e.parentNode.insertBefore(a, e.nextSibling) : e.parentNode.insertBefore(a, e) };
    return { init() { N(), f(), l() }, siap(e, a) { "function" == typeof e ? n(e, a) : p(e, "a") }, linked(e) { m(e) }, tungguAda(e) { v(e) }, linkClass: () => e.linkClass, load(e, a) { g[e] = {}, g[e].i = a.i, g[e].t = a.t, t(e, a) } }
}();
window.Content = Content, void 0 === window.siap && (window.siap = Content.siap), void 0 === window.tungguAda && (window.tungguAda = Content.tungguAda), $(() => { Content.init() });