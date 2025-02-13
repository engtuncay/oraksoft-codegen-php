(function () {
    const b = document.createElement("link").relList;
    if (b && b.supports && b.supports("modulepreload")) return;
    for (const D of document.querySelectorAll('link[rel="modulepreload"]')) C(D);
    new MutationObserver(D => {
        for (const k of D) if (k.type === "childList") for (const B of k.addedNodes) B.tagName === "LINK" && B.rel === "modulepreload" && C(B)
    }).observe(document, {childList: !0, subtree: !0});

    function m(D) {
        const k = {};
        return D.integrity && (k.integrity = D.integrity), D.referrerPolicy && (k.referrerPolicy = D.referrerPolicy), D.crossOrigin === "use-credentials" ? k.credentials = "include" : D.crossOrigin === "anonymous" ? k.credentials = "omit" : k.credentials = "same-origin", k
    }

    function C(D) {
        if (D.ep) return;
        D.ep = !0;
        const k = m(D);
        fetch(D.href, k)
    }
})();
const U = {
    Backspace: "Backspace",
    Clear: "Clear",
    Down: "ArrowDown",
    End: "End",
    Enter: "Enter",
    Escape: "Escape",
    Home: "Home",
    Space: " ",
    Up: "ArrowUp"
}, A = {Close: 0, CloseSelect: 1, First: 2, Last: 3, Next: 4, Open: 5, Previous: 6, Space: 8, Type: 9};

function Q(_ = [], b, m = []) {
    return _.filter(C => C.toLowerCase().indexOf(b.toLowerCase()) === 0 && m.indexOf(C) < 0)
}

function te(_, b) {
    const {key: m, altKey: C, ctrlKey: D, metaKey: k} = _;
    if (!b && (m === U.Down || m === U.Enter || m === U.Space)) return A.Open;
    if (m === U.Down) return A.Next;
    if (m === U.Up) return A.Previous;
    if (m === U.Home) return A.First;
    if (m === U.End) return A.Last;
    if (m === U.Escape) return A.Close;
    if (m === U.Enter) return A.CloseSelect;
    if (m === U.Space) return A.Space;
    if (m === U.Backspace || m === U.Clear || m.length === 1 && !C && !D && !k) return A.Type
}

function Ee(_, b) {
    const m = Q(_, b)[0], C = D => D.every(k => k === D[0]);
    if (console.log("testing string", b), m) return _.indexOf(m);
    if (C(b.split(""))) {
        const D = Q(_, b[0]), k = (b.length - 1) % D.length;
        return _.indexOf(D[k])
    } else return -1
}

function ne(_, b, m) {
    switch (m) {
        case A.First:
            return 0;
        case A.Last:
            return b;
        case A.Previous:
            return Math.max(0, _ - 1);
        case A.Next:
            return Math.min(b, _ + 1);
        default:
            return _
    }
}

function ie(_) {
    return _ && _.clientHeight < _.scrollHeight
}

function oe(_, b) {
    const {offsetHeight: m, offsetTop: C} = _, {offsetHeight: D, scrollTop: k} = b, B = C < k, W = C + m > k + D;
    B ? b.scrollTo(0, C) : W && b.scrollTo(0, C - D + m)
}

class Ce {
    constructor(b, m) {
        this.elDiv = b, this.elInput = b.querySelector("input"), this.elListBoxDiv = b.querySelector("[role=listbox]"), this.idBase = this.elInput.id, this.options = m, this.activeIndex = 0, this.open = !1
    }

    init() {
        this.getOptionsInit().length > 0 && (this.elInput.value = this.options[0]), this.elInput.addEventListener("input", this.onInput.bind(this)), this.elInput.addEventListener("blur", this.onInputBlur.bind(this)), this.elInput.addEventListener("click", () => this.updateMenuState(!0)), this.elInput.addEventListener("keydown", this.onInputKeyDown.bind(this)), this.options.map((b, m) => {
            const C = document.createElement("div");
            C.setAttribute("role", "option"), C.id = `${this.idBase}-${m}`, C.className = m === 0 ? "combo-option option-current" : "combo-option", C.setAttribute("aria-selected", `${m === 0}`), C.innerText = b, C.addEventListener("click", () => {
                this.onOptionClick(m)
            }), C.addEventListener("mousedown", this.onOptionMouseDown.bind(this)), this.elListBoxDiv.appendChild(C)
        })
    }

    onInput() {
        const b = this.elInput.value, m = Q(this.options, b), C = m.filter(k => k === this.options[this.activeIndex]);
        m.length > 0 && !C.length && this.onOptionChange(this.options.indexOf(m[0]));
        const D = this.options.length > 0;
        this.open !== D && this.updateMenuState(D, !1)
    }

    onInputKeyDown(b) {
        const m = this.options.length - 1, C = te(b, this.open);
        switch (C) {
            case A.Next:
            case A.Last:
            case A.First:
            case A.Previous:
                return b.preventDefault(), this.onOptionChange(ne(this.activeIndex, m, C));
            case A.CloseSelect:
                return b.preventDefault(), this.selectOption(this.activeIndex), this.updateMenuState(!1);
            case A.Close:
                return b.preventDefault(), this.updateMenuState(!1);
            case A.Open:
                return this.updateMenuState(!0)
        }
    }

    onInputBlur() {
        if (this.ignoreBlur) {
            this.ignoreBlur = !1;
            return
        }
        this.open && (this.selectOption(this.activeIndex), this.updateMenuState(!1, !1))
    }

    onOptionChange(b) {
        this.activeIndex = b, this.elInput.setAttribute("aria-activedescendant", `${this.idBase}-${b}`);
        const m = this.elDiv.querySelectorAll("[role=option]");
        [...m].forEach(C => {
            C.classList.remove("option-current")
        }), m[b].classList.add("option-current"), this.open && ie(this.elListBoxDiv) && oe(m[b], this.elListBoxDiv)
    }

    onOptionClick(b) {
        this.onOptionChange(b), this.selectOption(b), this.updateMenuState(!1)
    }

    onOptionMouseDown() {
        this.ignoreBlur = !0
    }

    selectOption(b) {
        const m = this.options[b];
        this.elInput.value = m, this.activeIndex = b;
        const C = this.elDiv.querySelectorAll("[role=option]");
        [...C].forEach(D => {
            D.setAttribute("aria-selected", "false")
        }), C[b].setAttribute("aria-selected", "true")
    }

    updateMenuState(b, m = !0) {
        this.open = b, this.elInput.setAttribute("aria-expanded", `${b}`), b ? this.elDiv.classList.add("open") : this.elDiv.classList.remove("open"), m && this.elInput.focus()
    }

    getOptionsInit() {
        return this.options == null && (this.options = []), this.options
    }
}

class xe {
    constructor(b, m) {
        this.elDiv = b, this.elInput = b.querySelector("input"), this.elListboxDiv = b.querySelector("[role=listbox]"), this.idBase = this.elInput.id, this.options = m, this.activeIndex = 0, this.open = !1
    }

    init() {
        this.getOptionsInit().length > 0 && (this.elInput.value = this.options[0]), this.elInput.addEventListener("input", this.onInput.bind(this)), this.elInput.addEventListener("blur", this.onInputBlur.bind(this)), this.elInput.addEventListener("click", () => this.updateMenuState(!0)), this.elInput.addEventListener("keydown", this.onInputKeyDown.bind(this)), this.options.map((b, m) => {
            const C = document.createElement("div");
            C.setAttribute("role", "option"), C.id = `${this.idBase}-${m}`, C.className = m === 0 ? "combo-option option-current" : "combo-option", C.setAttribute("aria-selected", `${m === 0}`), C.innerText = b, C.addEventListener("click", () => {
                this.onOptionClick(m)
            }), C.addEventListener("mousedown", this.onOptionMouseDown.bind(this)), this.elListboxDiv.appendChild(C)
        })
    }

    onInput() {
        const b = this.elInput.value, m = Q(this.options, b), C = m.filter(k => k === this.options[this.activeIndex]);
        m.length > 0 && !C.length && this.onOptionChange(this.options.indexOf(m[0]));
        const D = this.options.length > 0;
        this.open !== D && this.updateMenuState(D, !1)
    }

    onInputKeyDown(b) {
        const m = this.options.length - 1, C = te(b, this.open);
        switch (C) {
            case A.Next:
            case A.Last:
            case A.First:
            case A.Previous:
                return b.preventDefault(), this.onOptionChange(ne(this.activeIndex, m, C));
            case A.CloseSelect:
                return b.preventDefault(), this.selectOption(this.activeIndex), this.updateMenuState(!1);
            case A.Close:
                return b.preventDefault(), this.updateMenuState(!1);
            case A.Open:
                return this.updateMenuState(!0)
        }
    }

    onInputBlur() {
        if (this.ignoreBlur) {
            this.ignoreBlur = !1;
            return
        }
        this.open && (this.selectOption(this.activeIndex), this.updateMenuState(!1, !1))
    }

    onOptionChange(b) {
        this.activeIndex = b, this.elInput.setAttribute("aria-activedescendant", `${this.idBase}-${b}`);
        const m = this.elDiv.querySelectorAll("[role=option]");
        [...m].forEach(C => {
            C.classList.remove("option-current")
        }), m[b].classList.add("option-current"), this.open && ie(this.elListboxDiv) && oe(m[b], this.elListboxDiv)
    }

    onOptionClick(b) {
        this.onOptionChange(b), this.selectOption(b), this.updateMenuState(!1)
    }

    onOptionMouseDown() {
        this.ignoreBlur = !0
    }

    selectOption(b) {
        const m = this.options[b];
        this.elInput.value = m, this.activeIndex = b;
        const C = this.elDiv.querySelectorAll("[role=option]");
        [...C].forEach(D => {
            D.setAttribute("aria-selected", "false")
        }), C[b].setAttribute("aria-selected", "true")
    }

    updateMenuState(b, m = !0) {
        this.open = b, this.elInput.setAttribute("aria-expanded", `${b}`), b ? this.elDiv.classList.add("open") : this.elDiv.classList.remove("open"), m && this.elInput.focus()
    }

    getOptionsInit() {
        return this.options == null && (this.options = []), this.options
    }
}

class Se {
    constructor(b, m) {
        this.el = b, this.inputEl = b.querySelector("input"), this.listboxEl = b.querySelector("[role=listbox]"), this.idBase = this.inputEl.id, this.selectedEl = document.getElementById(`${this.idBase}-selected`), this.options = m, this.activeIndex = 0, this.open = !1
    }

    init() {
        this.inputEl.addEventListener("input", this.onInput.bind(this)), this.inputEl.addEventListener("blur", this.onInputBlur.bind(this)), this.inputEl.addEventListener("click", () => this.updateMenuState(!0)), this.inputEl.addEventListener("keydown", this.onInputKeyDown.bind(this)), this.listboxEl.addEventListener("blur", this.onInputBlur.bind(this)), this.options.map((b, m) => {
            const C = document.createElement("div");
            C.setAttribute("role", "option"), C.id = `${this.idBase}-${m}`, C.className = m === 0 ? "combo-option option-current" : "combo-option", C.setAttribute("aria-selected", "false"), C.innerText = b, C.addEventListener("click", () => {
                this.onOptionClick(m)
            }), C.addEventListener("mousedown", this.onOptionMouseDown.bind(this)), this.listboxEl.appendChild(C)
        })
    }

    onInput() {
        const b = this.inputEl.value, m = Q(this.options, b), C = m.filter(k => k === this.options[this.activeIndex]);
        m.length > 0 && !C.length && this.onOptionChange(this.options.indexOf(m[0]));
        const D = this.options.length > 0;
        this.open !== D && this.updateMenuState(D, !1)
    }

    onInputKeyDown(b) {
        const m = this.options.length - 1, C = te(b, this.open);
        switch (C) {
            case A.Next:
            case A.Last:
            case A.First:
            case A.Previous:
                return b.preventDefault(), this.onOptionChange(ne(this.activeIndex, m, C));
            case A.CloseSelect:
                return b.preventDefault(), this.updateOption(this.activeIndex);
            case A.Close:
                return b.preventDefault(), this.updateMenuState(!1);
            case A.Open:
                return this.updateMenuState(!0)
        }
    }

    onInputBlur() {
        if (this.ignoreBlur) {
            this.ignoreBlur = !1;
            return
        }
        this.open && this.updateMenuState(!1, !1)
    }

    onOptionChange(b) {
        this.activeIndex = b, this.inputEl.setAttribute("aria-activedescendant", `${this.idBase}-${b}`);
        const m = this.el.querySelectorAll("[role=option]");
        [...m].forEach(C => {
            C.classList.remove("option-current")
        }), m[b].classList.add("option-current"), this.open && ie(this.listboxEl) && oe(m[b], this.listboxEl)
    }

    onOptionClick(b) {
        this.onOptionChange(b), this.updateOption(b), this.inputEl.focus()
    }

    onOptionMouseDown() {
        this.ignoreBlur = !0
    }

    removeOption(b) {
        this.options[b];
        const m = this.el.querySelectorAll("[role=option]");
        m[b].setAttribute("aria-selected", "false"), m[b].classList.remove("option-selected");
        const C = document.getElementById(`${this.idBase}-remove-${b}`);
        this.selectedEl.removeChild(C.parentElement)
    }

    selectOption(b) {
        const m = this.options[b];
        this.activeIndex = b;
        const C = this.el.querySelectorAll("[role=option]");
        C[b].setAttribute("aria-selected", "true"), C[b].classList.add("option-selected");
        const D = document.createElement("button"), k = document.createElement("li");
        D.className = "remove-option", D.type = "button", D.id = `${this.idBase}-remove-${b}`, D.setAttribute("aria-describedby", `${this.idBase}-remove`), D.addEventListener("click", () => {
            this.removeOption(b)
        }), D.innerHTML = m + " ", k.appendChild(D), this.selectedEl.appendChild(k)
    }

    updateOption(b) {
        this.options[b], this.el.querySelectorAll("[role=option]")[b].getAttribute("aria-selected") === "true" ? this.removeOption(b) : this.selectOption(b), this.inputEl.value = ""
    }

    updateMenuState(b, m = !0) {
        this.open = b, this.inputEl.setAttribute("aria-expanded", `${b}`), b ? this.el.classList.add("open") : this.el.classList.remove("open"), m && this.inputEl.focus()
    }
}

class De {
    constructor(b, m) {
        this.el = b, this.comboEl = b.querySelector("[role=combobox]"), this.valueEl = this.comboEl.querySelector("span"), this.listboxEl = b.querySelector("[role=listbox]"), this.idBase = this.comboEl.id, this.options = m, this.activeIndex = 0, this.open = !1, this.searchString = "", this.searchTimeout = null
    }

    init() {
        this.valueEl.innerHTML = this.options[0], this.comboEl.addEventListener("blur", this.onComboBlur.bind(this)), this.comboEl.addEventListener("click", () => this.updateMenuState(!0)), this.comboEl.addEventListener("keydown", this.onComboKeyDown.bind(this)), this.options.map((b, m) => {
            const C = document.createElement("div");
            C.setAttribute("role", "option"), C.id = `${this.idBase}-${m}`, C.className = m === 0 ? "combo-option option-current" : "combo-option", C.setAttribute("aria-selected", `${m === 0}`), C.innerText = b, C.addEventListener("click", D => {
                D.stopPropagation(), this.onOptionClick(m)
            }), C.addEventListener("mousedown", this.onOptionMouseDown.bind(this)), this.listboxEl.appendChild(C)
        })
    }

    getSearchString(b) {
        return typeof this.searchTimeout == "number" && window.clearTimeout(this.searchTimeout), this.searchTimeout = window.setTimeout(() => {
            this.searchString = ""
        }, 1e3), this.searchString += b, this.searchString
    }

    onComboKeyDown(b) {
        const {key: m} = b, C = this.options.length - 1, D = te(b, this.open);
        switch (D) {
            case A.Next:
            case A.Last:
            case A.First:
            case A.Previous:
                return b.preventDefault(), this.onOptionChange(ne(this.activeIndex, C, D));
            case A.CloseSelect:
            case A.Space:
                b.preventDefault(), this.selectOption(this.activeIndex);
            case A.Close:
                return b.preventDefault(), this.updateMenuState(!1);
            case A.Type:
                this.updateMenuState(!0);
                var k = this.getSearchString(m);
                return this.onOptionChange(Math.max(0, Ee(this.options, k)));
            case A.Open:
                return b.preventDefault(), this.updateMenuState(!0)
        }
    }

    onComboBlur() {
        if (this.ignoreBlur) {
            this.ignoreBlur = !1;
            return
        }
        this.open && (this.selectOption(this.activeIndex), this.updateMenuState(!1, !1))
    }

    onOptionChange(b) {
        this.activeIndex = b, this.comboEl.setAttribute("aria-activedescendant", `${this.idBase}-${b}`);
        const m = this.el.querySelectorAll("[role=option]");
        [...m].forEach(C => {
            C.classList.remove("option-current")
        }), m[b].classList.add("option-current"), ie(this.listboxEl) && oe(m[b], this.listboxEl)
    }

    onOptionClick(b) {
        this.onOptionChange(b), this.selectOption(b), this.updateMenuState(!1)
    }

    onOptionMouseDown() {
        this.ignoreBlur = !0
    }

    selectOption(b) {
        const m = this.options[b];
        this.valueEl.innerHTML = m, this.activeIndex = b;
        const C = this.el.querySelectorAll("[role=option]");
        [...C].forEach(D => {
            D.setAttribute("aria-selected", "false")
        }), C[b].setAttribute("aria-selected", "true")
    }

    updateMenuState(b, m = !0) {
        this.open = b, this.comboEl.setAttribute("aria-expanded", `${b}`), b ? this.el.classList.add("open") : this.el.classList.remove("open"), m && this.comboEl.focus();
        const C = b ? `${this.idBase}-${this.activeIndex}` : this.valueEl.id;
        this.comboEl.setAttribute("aria-activedescendant", C)
    }
}

var le = {exports: {}};/*!
 * Knockout JavaScript library v3.5.1
 * (c) The Knockout.js team - http://knockoutjs.com/
 * License: MIT (http://www.opensource.org/licenses/mit-license.php)
 */
(function (_, b) {
    (function () {
        (function (m) {
            var C = this || (0, eval)("this"), D = C.document, k = C.navigator, B = C.jQuery, W = C.JSON;
            B || typeof jQuery > "u" || (B = jQuery), function (ee) {
                ee(_.exports || b)
            }(function (ee, ce) {
                function ae(t, n) {
                    return t === null || typeof t in ye ? t === n : !1
                }

                function he(t, n) {
                    var i;
                    return function () {
                        i || (i = e.a.setTimeout(function () {
                            i = m, t()
                        }, n))
                    }
                }

                function de(t, n) {
                    var i;
                    return function () {
                        clearTimeout(i), i = e.a.setTimeout(t, n)
                    }
                }

                function ve(t, n) {
                    n && n !== "change" ? n === "beforeChange" ? this.pc(t) : this.gb(t, n) : this.qc(t)
                }

                function me(t, n) {
                    n !== null && n.s && n.s()
                }

                function ge(t, n) {
                    var i = this.qd, o = i[M];
                    o.ra || (this.Qb && this.mb[n] ? (i.uc(n, t, this.mb[n]), this.mb[n] = null, --this.Qb) : o.I[n] || i.uc(n, t, o.J ? {da: t} : i.$c(t)), t.Ja && t.gd())
                }

                var e = typeof ee < "u" ? ee : {};
                e.b = function (t, n) {
                    for (var i = t.split("."), o = e, u = 0; u < i.length - 1; u++) o = o[i[u]];
                    o[i[i.length - 1]] = n
                }, e.L = function (t, n, i) {
                    t[n] = i
                }, e.version = "3.5.1", e.b("version", e.version), e.options = {
                    deferUpdates: !1,
                    useOnlyNativeEvents: !1,
                    foreachHidesDestroyed: !1
                }, e.a = function () {
                    function t(r, v) {
                        for (var p in r) u.call(r, p) && v(p, r[p])
                    }

                    function n(r, v) {
                        if (v) for (var p in v) u.call(v, p) && (r[p] = v[p]);
                        return r
                    }

                    function i(r, v) {
                        return r.__proto__ = v, r
                    }

                    function o(r, v, p, w) {
                        var x = r[v].match(d) || [];
                        e.a.D(p.match(d), function (g) {
                            e.a.Na(x, g, w)
                        }), r[v] = x.join(" ")
                    }

                    var u = Object.prototype.hasOwnProperty, c = {__proto__: []} instanceof Array,
                        l = typeof Symbol == "function", f = {}, s = {};
                    f[k && /Firefox\/2/i.test(k.userAgent) ? "KeyboardEvent" : "UIEvents"] = ["keyup", "keydown", "keypress"], f.MouseEvents = "click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave".split(" "), t(f, function (r, v) {
                        if (v.length) for (var p = 0, w = v.length; p < w; p++) s[v[p]] = r
                    });
                    var a = {propertychange: !0}, h = D && function () {
                        for (var r = 3, v = D.createElement("div"), p = v.getElementsByTagName("i"); v.innerHTML = "<!--[if gt IE " + ++r + "]><i></i><![endif]-->", p[0];) ;
                        return 4 < r ? r : m
                    }(), d = /\S+/g, y;
                    return {
                        Jc: ["authenticity_token", /^__RequestVerificationToken(_.*)?$/], D: function (r, v, p) {
                            for (var w = 0, x = r.length; w < x; w++) v.call(p, r[w], w, r)
                        }, A: typeof Array.prototype.indexOf == "function" ? function (r, v) {
                            return Array.prototype.indexOf.call(r, v)
                        } : function (r, v) {
                            for (var p = 0, w = r.length; p < w; p++) if (r[p] === v) return p;
                            return -1
                        }, Lb: function (r, v, p) {
                            for (var w = 0, x = r.length; w < x; w++) if (v.call(p, r[w], w, r)) return r[w];
                            return m
                        }, Pa: function (r, v) {
                            var p = e.a.A(r, v);
                            0 < p ? r.splice(p, 1) : p === 0 && r.shift()
                        }, wc: function (r) {
                            var v = [];
                            return r && e.a.D(r, function (p) {
                                0 > e.a.A(v, p) && v.push(p)
                            }), v
                        }, Mb: function (r, v, p) {
                            var w = [];
                            if (r) for (var x = 0, g = r.length; x < g; x++) w.push(v.call(p, r[x], x));
                            return w
                        }, jb: function (r, v, p) {
                            var w = [];
                            if (r) for (var x = 0, g = r.length; x < g; x++) v.call(p, r[x], x) && w.push(r[x]);
                            return w
                        }, Nb: function (r, v) {
                            if (v instanceof Array) r.push.apply(r, v); else for (var p = 0, w = v.length; p < w; p++) r.push(v[p]);
                            return r
                        }, Na: function (r, v, p) {
                            var w = e.a.A(e.a.bc(r), v);
                            0 > w ? p && r.push(v) : p || r.splice(w, 1)
                        }, Ba: c, extend: n, setPrototypeOf: i, Ab: c ? i : n, P: t, Ga: function (r, v, p) {
                            if (!r) return r;
                            var w = {}, x;
                            for (x in r) u.call(r, x) && (w[x] = v.call(p, r[x], x, r));
                            return w
                        }, Tb: function (r) {
                            for (; r.firstChild;) e.removeNode(r.firstChild)
                        }, Yb: function (r) {
                            r = e.a.la(r);
                            for (var v = (r[0] && r[0].ownerDocument || D).createElement("div"), p = 0, w = r.length; p < w; p++) v.appendChild(e.oa(r[p]));
                            return v
                        }, Ca: function (r, v) {
                            for (var p = 0, w = r.length, x = []; p < w; p++) {
                                var g = r[p].cloneNode(!0);
                                x.push(v ? e.oa(g) : g)
                            }
                            return x
                        }, va: function (r, v) {
                            if (e.a.Tb(r), v) for (var p = 0, w = v.length; p < w; p++) r.appendChild(v[p])
                        }, Xc: function (r, v) {
                            var p = r.nodeType ? [r] : r;
                            if (0 < p.length) {
                                for (var w = p[0], x = w.parentNode, g = 0, E = v.length; g < E; g++) x.insertBefore(v[g], w);
                                for (g = 0, E = p.length; g < E; g++) e.removeNode(p[g])
                            }
                        }, Ua: function (r, v) {
                            if (r.length) {
                                for (v = v.nodeType === 8 && v.parentNode || v; r.length && r[0].parentNode !== v;) r.splice(0, 1);
                                for (; 1 < r.length && r[r.length - 1].parentNode !== v;) r.length--;
                                if (1 < r.length) {
                                    var p = r[0], w = r[r.length - 1];
                                    for (r.length = 0; p !== w;) r.push(p), p = p.nextSibling;
                                    r.push(w)
                                }
                            }
                            return r
                        }, Zc: function (r, v) {
                            7 > h ? r.setAttribute("selected", v) : r.selected = v
                        }, Db: function (r) {
                            return r === null || r === m ? "" : r.trim ? r.trim() : r.toString().replace(/^[\s\xa0]+|[\s\xa0]+$/g, "")
                        }, Ud: function (r, v) {
                            return r = r || "", v.length > r.length ? !1 : r.substring(0, v.length) === v
                        }, vd: function (r, v) {
                            if (r === v) return !0;
                            if (r.nodeType === 11) return !1;
                            if (v.contains) return v.contains(r.nodeType !== 1 ? r.parentNode : r);
                            if (v.compareDocumentPosition) return (v.compareDocumentPosition(r) & 16) == 16;
                            for (; r && r != v;) r = r.parentNode;
                            return !!r
                        }, Sb: function (r) {
                            return e.a.vd(r, r.ownerDocument.documentElement)
                        }, kd: function (r) {
                            return !!e.a.Lb(r, e.a.Sb)
                        }, R: function (r) {
                            return r && r.tagName && r.tagName.toLowerCase()
                        }, Ac: function (r) {
                            return e.onError ? function () {
                                try {
                                    return r.apply(this, arguments)
                                } catch (v) {
                                    throw e.onError && e.onError(v), v
                                }
                            } : r
                        }, setTimeout: function (r, v) {
                            return setTimeout(e.a.Ac(r), v)
                        }, Gc: function (r) {
                            setTimeout(function () {
                                throw e.onError && e.onError(r), r
                            }, 0)
                        }, B: function (r, v, p) {
                            var w = e.a.Ac(p);
                            if (p = a[v], e.options.useOnlyNativeEvents || p || !B) if (p || typeof r.addEventListener != "function") if (typeof r.attachEvent < "u") {
                                var x = function (E) {
                                    w.call(r, E)
                                }, g = "on" + v;
                                r.attachEvent(g, x), e.a.K.za(r, function () {
                                    r.detachEvent(g, x)
                                })
                            } else throw Error("Browser doesn't support addEventListener or attachEvent"); else r.addEventListener(v, w, !1); else y || (y = typeof B(r).on == "function" ? "on" : "bind"), B(r)[y](v, w)
                        }, Fb: function (r, v) {
                            if (!r || !r.nodeType) throw Error("element must be a DOM node when calling triggerEvent");
                            var p;
                            if (e.a.R(r) === "input" && r.type && v.toLowerCase() == "click" ? (p = r.type, p = p == "checkbox" || p == "radio") : p = !1, e.options.useOnlyNativeEvents || !B || p) if (typeof D.createEvent == "function") if (typeof r.dispatchEvent == "function") p = D.createEvent(s[v] || "HTMLEvents"), p.initEvent(v, !0, !0, C, 0, 0, 0, 0, 0, !1, !1, !1, !1, 0, r), r.dispatchEvent(p); else throw Error("The supplied element doesn't support dispatchEvent"); else if (p && r.click) r.click(); else if (typeof r.fireEvent < "u") r.fireEvent("on" + v); else throw Error("Browser doesn't support triggering events"); else B(r).trigger(v)
                        }, f: function (r) {
                            return e.O(r) ? r() : r
                        }, bc: function (r) {
                            return e.O(r) ? r.v() : r
                        }, Eb: function (r, v, p) {
                            var w;
                            v && (typeof r.classList == "object" ? (w = r.classList[p ? "add" : "remove"], e.a.D(v.match(d), function (x) {
                                w.call(r.classList, x)
                            })) : typeof r.className.baseVal == "string" ? o(r.className, "baseVal", v, p) : o(r, "className", v, p))
                        }, Bb: function (r, v) {
                            var p = e.a.f(v);
                            (p === null || p === m) && (p = "");
                            var w = e.h.firstChild(r);
                            !w || w.nodeType != 3 || e.h.nextSibling(w) ? e.h.va(r, [r.ownerDocument.createTextNode(p)]) : w.data = p, e.a.Ad(r)
                        }, Yc: function (r, v) {
                            if (r.name = v, 7 >= h) try {
                                var p = r.name.replace(/[&<>'"]/g, function (w) {
                                    return "&#" + w.charCodeAt(0) + ";"
                                });
                                r.mergeAttributes(D.createElement("<input name='" + p + "'/>"), !1)
                            } catch {
                            }
                        }, Ad: function (r) {
                            9 <= h && (r = r.nodeType == 1 ? r : r.parentNode, r.style && (r.style.zoom = r.style.zoom))
                        }, wd: function (r) {
                            if (h) {
                                var v = r.style.width;
                                r.style.width = 0, r.style.width = v
                            }
                        }, Pd: function (r, v) {
                            r = e.a.f(r), v = e.a.f(v);
                            for (var p = [], w = r; w <= v; w++) p.push(w);
                            return p
                        }, la: function (r) {
                            for (var v = [], p = 0, w = r.length; p < w; p++) v.push(r[p]);
                            return v
                        }, Da: function (r) {
                            return l ? Symbol(r) : r
                        }, Zd: h === 6, $d: h === 7, W: h, Lc: function (r, v) {
                            for (var p = e.a.la(r.getElementsByTagName("input")).concat(e.a.la(r.getElementsByTagName("textarea"))), w = typeof v == "string" ? function (E) {
                                return E.name === v
                            } : function (E) {
                                return v.test(E.name)
                            }, x = [], g = p.length - 1; 0 <= g; g--) w(p[g]) && x.push(p[g]);
                            return x
                        }, Nd: function (r) {
                            return typeof r == "string" && (r = e.a.Db(r)) ? W && W.parse ? W.parse(r) : new Function("return " + r)() : null
                        }, hc: function (r, v, p) {
                            if (!W || !W.stringify) throw Error("Cannot find JSON.stringify(). Some browsers (e.g., IE < 8) don't support it natively, but you can overcome this by adding a script reference to json2.js, downloadable from http://www.json.org/json2.js");
                            return W.stringify(e.a.f(r), v, p)
                        }, Od: function (r, v, p) {
                            p = p || {};
                            var w = p.params || {}, x = p.includeFields || this.Jc, g = r;
                            if (typeof r == "object" && e.a.R(r) === "form") for (var g = r.action, E = x.length - 1; 0 <= E; E--) for (var S = e.a.Lc(r, x[E]), O = S.length - 1; 0 <= O; O--) w[S[O].name] = S[O].value;
                            v = e.a.f(v);
                            var T = D.createElement("form");
                            T.style.display = "none", T.action = g, T.method = "post";
                            for (var N in v) r = D.createElement("input"), r.type = "hidden", r.name = N, r.value = e.a.hc(e.a.f(v[N])), T.appendChild(r);
                            t(w, function (I, F) {
                                var $ = D.createElement("input");
                                $.type = "hidden", $.name = I, $.value = F, T.appendChild($)
                            }), D.body.appendChild(T), p.submitter ? p.submitter(T) : T.submit(), setTimeout(function () {
                                T.parentNode.removeChild(T)
                            }, 0)
                        }
                    }
                }(), e.b("utils", e.a), e.b("utils.arrayForEach", e.a.D), e.b("utils.arrayFirst", e.a.Lb), e.b("utils.arrayFilter", e.a.jb), e.b("utils.arrayGetDistinctValues", e.a.wc), e.b("utils.arrayIndexOf", e.a.A), e.b("utils.arrayMap", e.a.Mb), e.b("utils.arrayPushAll", e.a.Nb), e.b("utils.arrayRemoveItem", e.a.Pa), e.b("utils.cloneNodes", e.a.Ca), e.b("utils.createSymbolOrString", e.a.Da), e.b("utils.extend", e.a.extend), e.b("utils.fieldsIncludedWithJsonPost", e.a.Jc), e.b("utils.getFormFields", e.a.Lc), e.b("utils.objectMap", e.a.Ga), e.b("utils.peekObservable", e.a.bc), e.b("utils.postJson", e.a.Od), e.b("utils.parseJson", e.a.Nd), e.b("utils.registerEventHandler", e.a.B), e.b("utils.stringifyJson", e.a.hc), e.b("utils.range", e.a.Pd), e.b("utils.toggleDomNodeCssClass", e.a.Eb), e.b("utils.triggerEvent", e.a.Fb), e.b("utils.unwrapObservable", e.a.f), e.b("utils.objectForEach", e.a.P), e.b("utils.addOrRemoveItem", e.a.Na), e.b("utils.setTextContent", e.a.Bb), e.b("unwrap", e.a.f), Function.prototype.bind || (Function.prototype.bind = function (t) {
                    var n = this;
                    if (arguments.length === 1) return function () {
                        return n.apply(t, arguments)
                    };
                    var i = Array.prototype.slice.call(arguments, 1);
                    return function () {
                        var o = i.slice(0);
                        return o.push.apply(o, arguments), n.apply(t, o)
                    }
                }), e.a.g = new function () {
                    var t = 0, n = "__ko__" + new Date().getTime(), i = {}, o, u;
                    return e.a.W ? (o = function (c, l) {
                        var f = c[n];
                        if (!f || f === "null" || !i[f]) {
                            if (!l) return m;
                            f = c[n] = "ko" + t++, i[f] = {}
                        }
                        return i[f]
                    }, u = function (c) {
                        var l = c[n];
                        return l ? (delete i[l], c[n] = null, !0) : !1
                    }) : (o = function (c, l) {
                        var f = c[n];
                        return !f && l && (f = c[n] = {}), f
                    }, u = function (c) {
                        return c[n] ? (delete c[n], !0) : !1
                    }), {
                        get: function (c, l) {
                            var f = o(c, !1);
                            return f && f[l]
                        }, set: function (c, l, f) {
                            (c = o(c, f !== m)) && (c[l] = f)
                        }, Ub: function (c, l, f) {
                            return c = o(c, !0), c[l] || (c[l] = f)
                        }, clear: u, Z: function () {
                            return t++ + n
                        }
                    }
                }, e.b("utils.domData", e.a.g), e.b("utils.domData.clear", e.a.g.clear), e.a.K = new function () {
                    function t(l, f) {
                        var s = e.a.g.get(l, o);
                        return s === m && f && (s = [], e.a.g.set(l, o, s)), s
                    }

                    function n(l) {
                        var f = t(l, !1);
                        if (f) for (var f = f.slice(0), s = 0; s < f.length; s++) f[s](l);
                        e.a.g.clear(l), e.a.K.cleanExternalData(l), c[l.nodeType] && i(l.childNodes, !0)
                    }

                    function i(l, f) {
                        for (var s = [], a, h = 0; h < l.length; h++) if ((!f || l[h].nodeType === 8) && (n(s[s.length] = a = l[h]), l[h] !== a)) for (; h-- && e.a.A(s, l[h]) == -1;) ;
                    }

                    var o = e.a.g.Z(), u = {1: !0, 8: !0, 9: !0}, c = {1: !0, 9: !0};
                    return {
                        za: function (l, f) {
                            if (typeof f != "function") throw Error("Callback must be a function");
                            t(l, !0).push(f)
                        }, yb: function (l, f) {
                            var s = t(l, !1);
                            s && (e.a.Pa(s, f), s.length == 0 && e.a.g.set(l, o, m))
                        }, oa: function (l) {
                            return e.u.G(function () {
                                u[l.nodeType] && (n(l), c[l.nodeType] && i(l.getElementsByTagName("*")))
                            }), l
                        }, removeNode: function (l) {
                            e.oa(l), l.parentNode && l.parentNode.removeChild(l)
                        }, cleanExternalData: function (l) {
                            B && typeof B.cleanData == "function" && B.cleanData([l])
                        }
                    }
                }, e.oa = e.a.K.oa, e.removeNode = e.a.K.removeNode, e.b("cleanNode", e.oa), e.b("removeNode", e.removeNode), e.b("utils.domNodeDisposal", e.a.K), e.b("utils.domNodeDisposal.addDisposeCallback", e.a.K.za), e.b("utils.domNodeDisposal.removeDisposeCallback", e.a.K.yb), function () {
                    var t = [0, "", ""], n = [1, "<table>", "</table>"],
                        i = [3, "<table><tbody><tr>", "</tr></tbody></table>"],
                        o = [1, "<select multiple='multiple'>", "</select>"], u = {
                            thead: n,
                            tbody: n,
                            tfoot: n,
                            tr: [2, "<table><tbody>", "</tbody></table>"],
                            td: i,
                            th: i,
                            option: o,
                            optgroup: o
                        }, c = 8 >= e.a.W;
                    e.a.ua = function (l, f) {
                        var s;
                        if (B) {
                            if (B.parseHTML) s = B.parseHTML(l, f) || []; else if ((s = B.clean([l], f)) && s[0]) {
                                for (var a = s[0]; a.parentNode && a.parentNode.nodeType !== 11;) a = a.parentNode;
                                a.parentNode && a.parentNode.removeChild(a)
                            }
                        } else {
                            (s = f) || (s = D);
                            var a = s.parentWindow || s.defaultView || C, h = e.a.Db(l).toLowerCase(),
                                d = s.createElement("div"), y;
                            for (y = (h = h.match(/^(?:\x3c!--.*?--\x3e\s*?)*?<([a-z]+)[\s>]/)) && u[h[1]] || t, h = y[0], y = "ignored<div>" + y[1] + l + y[2] + "</div>", typeof a.innerShiv == "function" ? d.appendChild(a.innerShiv(y)) : (c && s.body.appendChild(d), d.innerHTML = y, c && d.parentNode.removeChild(d)); h--;) d = d.lastChild;
                            s = e.a.la(d.lastChild.childNodes)
                        }
                        return s
                    }, e.a.Md = function (l, f) {
                        var s = e.a.ua(l, f);
                        return s.length && s[0].parentElement || e.a.Yb(s)
                    }, e.a.fc = function (l, f) {
                        if (e.a.Tb(l), f = e.a.f(f), f !== null && f !== m) if (typeof f != "string" && (f = f.toString()), B) B(l).html(f); else for (var s = e.a.ua(f, l.ownerDocument), a = 0; a < s.length; a++) l.appendChild(s[a])
                    }
                }(), e.b("utils.parseHtmlFragment", e.a.ua), e.b("utils.setHtml", e.a.fc), e.aa = function () {
                    function t(i, o) {
                        if (i) {
                            if (i.nodeType == 8) {
                                var u = e.aa.Uc(i.nodeValue);
                                u != null && o.push({ud: i, Kd: u})
                            } else if (i.nodeType == 1) for (var u = 0, c = i.childNodes, l = c.length; u < l; u++) t(c[u], o)
                        }
                    }

                    var n = {};
                    return {
                        Xb: function (i) {
                            if (typeof i != "function") throw Error("You can only pass a function to ko.memoization.memoize()");
                            var o = (4294967296 * (1 + Math.random()) | 0).toString(16).substring(1) + (4294967296 * (1 + Math.random()) | 0).toString(16).substring(1);
                            return n[o] = i, "<!--[ko_memo:" + o + "]-->"
                        }, bd: function (i, o) {
                            var u = n[i];
                            if (u === m) throw Error("Couldn't find any memo with ID " + i + ". Perhaps it's already been unmemoized.");
                            try {
                                return u.apply(null, o || []), !0
                            } finally {
                                delete n[i]
                            }
                        }, cd: function (i, o) {
                            var u = [];
                            t(i, u);
                            for (var c = 0, l = u.length; c < l; c++) {
                                var f = u[c].ud, s = [f];
                                o && e.a.Nb(s, o), e.aa.bd(u[c].Kd, s), f.nodeValue = "", f.parentNode && f.parentNode.removeChild(f)
                            }
                        }, Uc: function (i) {
                            return (i = i.match(/^\[ko_memo\:(.*?)\]$/)) ? i[1] : null
                        }
                    }
                }(), e.b("memoization", e.aa), e.b("memoization.memoize", e.aa.Xb), e.b("memoization.unmemoize", e.aa.bd), e.b("memoization.parseMemoText", e.aa.Uc), e.b("memoization.unmemoizeDomNodeAndDescendants", e.aa.cd), e.na = function () {
                    function t() {
                        if (u) {
                            for (var f = u, s = 0, a; l < u;) if (a = o[l++]) {
                                if (l > f) {
                                    if (5e3 <= ++s) {
                                        l = u, e.a.Gc(Error("'Too much recursion' after processing " + s + " task groups."));
                                        break
                                    }
                                    f = u
                                }
                                try {
                                    a()
                                } catch (h) {
                                    e.a.Gc(h)
                                }
                            }
                        }
                    }

                    function n() {
                        t(), l = u = o.length = 0
                    }

                    var i, o = [], u = 0, c = 1, l = 0;
                    return C.MutationObserver ? i = function (f) {
                        var s = D.createElement("div");
                        return new MutationObserver(f).observe(s, {attributes: !0}), function () {
                            s.classList.toggle("foo")
                        }
                    }(n) : i = D && "onreadystatechange" in D.createElement("script") ? function (f) {
                        var s = D.createElement("script");
                        s.onreadystatechange = function () {
                            s.onreadystatechange = null, D.documentElement.removeChild(s), s = null, f()
                        }, D.documentElement.appendChild(s)
                    } : function (f) {
                        setTimeout(f, 0)
                    }, {
                        scheduler: i, zb: function (f) {
                            return u || e.na.scheduler(n), o[u++] = f, c++
                        }, cancel: function (f) {
                            f = f - (c - u), f >= l && f < u && (o[f] = null)
                        }, resetForTesting: function () {
                            var f = u - l;
                            return l = u = o.length = 0, f
                        }, Sd: t
                    }
                }(), e.b("tasks", e.na), e.b("tasks.schedule", e.na.zb), e.b("tasks.runEarly", e.na.Sd), e.Ta = {
                    throttle: function (t, n) {
                        t.throttleEvaluation = n;
                        var i = null;
                        return e.$({
                            read: t, write: function (o) {
                                clearTimeout(i), i = e.a.setTimeout(function () {
                                    t(o)
                                }, n)
                            }
                        })
                    }, rateLimit: function (t, n) {
                        var i, o, u;
                        typeof n == "number" ? i = n : (i = n.timeout, o = n.method), t.Hb = !1, u = typeof o == "function" ? o : o == "notifyWhenChangesStop" ? de : he, t.ub(function (c) {
                            return u(c, i, n)
                        })
                    }, deferred: function (t, n) {
                        if (n !== !0) throw Error("The 'deferred' extender only accepts the value 'true', because it is not supported to turn deferral off once enabled.");
                        t.Hb || (t.Hb = !0, t.ub(function (i) {
                            var o, u = !1;
                            return function () {
                                if (!u) {
                                    e.na.cancel(o), o = e.na.zb(i);
                                    try {
                                        u = !0, t.notifySubscribers(m, "dirty")
                                    } finally {
                                        u = !1
                                    }
                                }
                            }
                        }))
                    }, notify: function (t, n) {
                        t.equalityComparer = n == "always" ? null : ae
                    }
                };
                var ye = {undefined: 1, boolean: 1, number: 1, string: 1};
                e.b("extenders", e.Ta), e.ic = function (t, n, i) {
                    this.da = t, this.lc = n, this.mc = i, this.Ib = !1, this.fb = this.Jb = null, e.L(this, "dispose", this.s), e.L(this, "disposeWhenNodeIsRemoved", this.l)
                }, e.ic.prototype.s = function () {
                    this.Ib || (this.fb && e.a.K.yb(this.Jb, this.fb), this.Ib = !0, this.mc(), this.da = this.lc = this.mc = this.Jb = this.fb = null)
                }, e.ic.prototype.l = function (t) {
                    this.Jb = t, e.a.K.za(t, this.fb = this.s.bind(this))
                }, e.T = function () {
                    e.a.Ab(this, P), P.qb(this)
                };
                var P = {
                    qb: function (t) {
                        t.U = {change: []}, t.sc = 1
                    }, subscribe: function (t, n, i) {
                        var o = this;
                        i = i || "change";
                        var u = new e.ic(o, n ? t.bind(n) : t, function () {
                            e.a.Pa(o.U[i], u), o.hb && o.hb(i)
                        });
                        return o.Qa && o.Qa(i), o.U[i] || (o.U[i] = []), o.U[i].push(u), u
                    }, notifySubscribers: function (t, n) {
                        if (n = n || "change", n === "change" && this.Gb(), this.Wa(n)) {
                            var i = n === "change" && this.ed || this.U[n].slice(0);
                            try {
                                e.u.xc();
                                for (var o = 0, u; u = i[o]; ++o) u.Ib || u.lc(t)
                            } finally {
                                e.u.end()
                            }
                        }
                    }, ob: function () {
                        return this.sc
                    }, Dd: function (t) {
                        return this.ob() !== t
                    }, Gb: function () {
                        ++this.sc
                    }, ub: function (t) {
                        var n = this, i = e.O(n), o, u, c, l, f;
                        n.gb || (n.gb = n.notifySubscribers, n.notifySubscribers = ve);
                        var s = t(function () {
                            n.Ja = !1, i && l === n && (l = n.nc ? n.nc() : n());
                            var a = u || f && n.sb(c, l);
                            f = u = o = !1, a && n.gb(c = l)
                        });
                        n.qc = function (a, h) {
                            h && n.Ja || (f = !h), n.ed = n.U.change.slice(0), n.Ja = o = !0, l = a, s()
                        }, n.pc = function (a) {
                            o || (c = a, n.gb(a, "beforeChange"))
                        }, n.rc = function () {
                            f = !0
                        }, n.gd = function () {
                            n.sb(c, n.v(!0)) && (u = !0)
                        }
                    }, Wa: function (t) {
                        return this.U[t] && this.U[t].length
                    }, Bd: function (t) {
                        if (t) return this.U[t] && this.U[t].length || 0;
                        var n = 0;
                        return e.a.P(this.U, function (i, o) {
                            i !== "dirty" && (n += o.length)
                        }), n
                    }, sb: function (t, n) {
                        return !this.equalityComparer || !this.equalityComparer(t, n)
                    }, toString: function () {
                        return "[object Object]"
                    }, extend: function (t) {
                        var n = this;
                        return t && e.a.P(t, function (i, o) {
                            var u = e.Ta[i];
                            typeof u == "function" && (n = u(n, o) || n)
                        }), n
                    }
                };
                e.L(P, "init", P.qb), e.L(P, "subscribe", P.subscribe), e.L(P, "extend", P.extend), e.L(P, "getSubscriptionsCount", P.Bd), e.a.Ba && e.a.setPrototypeOf(P, Function.prototype), e.T.fn = P, e.Qc = function (t) {
                    return t != null && typeof t.subscribe == "function" && typeof t.notifySubscribers == "function"
                }, e.b("subscribable", e.T), e.b("isSubscribable", e.Qc), e.S = e.u = function () {
                    function t(c) {
                        i.push(o), o = c
                    }

                    function n() {
                        o = i.pop()
                    }

                    var i = [], o, u = 0;
                    return {
                        xc: t, end: n, cc: function (c) {
                            if (o) {
                                if (!e.Qc(c)) throw Error("Only subscribable things can act as dependencies");
                                o.od.call(o.pd, c, c.fd || (c.fd = ++u))
                            }
                        }, G: function (c, l, f) {
                            try {
                                return t(), c.apply(l, f || [])
                            } finally {
                                n()
                            }
                        }, qa: function () {
                            if (o) return o.o.qa()
                        }, Va: function () {
                            if (o) return o.o.Va()
                        }, Ya: function () {
                            if (o) return o.Ya
                        }, o: function () {
                            if (o) return o.o
                        }
                    }
                }(), e.b("computedContext", e.S), e.b("computedContext.getDependenciesCount", e.S.qa), e.b("computedContext.getDependencies", e.S.Va), e.b("computedContext.isInitial", e.S.Ya), e.b("computedContext.registerDependency", e.S.cc), e.b("ignoreDependencies", e.Yd = e.u.G);
                var G = e.a.Da("_latestValue");
                e.ta = function (t) {
                    function n() {
                        return 0 < arguments.length ? (n.sb(n[G], arguments[0]) && (n.ya(), n[G] = arguments[0], n.xa()), this) : (e.u.cc(n), n[G])
                    }

                    return n[G] = t, e.a.Ba || e.a.extend(n, e.T.fn), e.T.fn.qb(n), e.a.Ab(n, V), e.options.deferUpdates && e.Ta.deferred(n, !0), n
                };
                var V = {
                    equalityComparer: ae, v: function () {
                        return this[G]
                    }, xa: function () {
                        this.notifySubscribers(this[G], "spectate"), this.notifySubscribers(this[G])
                    }, ya: function () {
                        this.notifySubscribers(this[G], "beforeChange")
                    }
                };
                e.a.Ba && e.a.setPrototypeOf(V, e.T.fn);
                var Y = e.ta.Ma = "__ko_proto__";
                V[Y] = e.ta, e.O = function (t) {
                    if ((t = typeof t == "function" && t[Y]) && t !== V[Y] && t !== e.o.fn[Y]) throw Error("Invalid object that looks like an observable; possibly from another Knockout instance");
                    return !!t
                }, e.Za = function (t) {
                    return typeof t == "function" && (t[Y] === V[Y] || t[Y] === e.o.fn[Y] && t.Nc)
                }, e.b("observable", e.ta), e.b("isObservable", e.O), e.b("isWriteableObservable", e.Za), e.b("isWritableObservable", e.Za), e.b("observable.fn", V), e.L(V, "peek", V.v), e.L(V, "valueHasMutated", V.xa), e.L(V, "valueWillMutate", V.ya), e.Ha = function (t) {
                    if (t = t || [], typeof t != "object" || !("length" in t)) throw Error("The argument passed when initializing an observable array must be an array, or null, or undefined.");
                    return t = e.ta(t), e.a.Ab(t, e.Ha.fn), t.extend({trackArrayChanges: !0})
                }, e.Ha.fn = {
                    remove: function (t) {
                        for (var n = this.v(), i = [], o = typeof t != "function" || e.O(t) ? function (l) {
                            return l === t
                        } : t, u = 0; u < n.length; u++) {
                            var c = n[u];
                            if (o(c)) {
                                if (i.length === 0 && this.ya(), n[u] !== c) throw Error("Array modified during remove; cannot remove item");
                                i.push(c), n.splice(u, 1), u--
                            }
                        }
                        return i.length && this.xa(), i
                    }, removeAll: function (t) {
                        if (t === m) {
                            var n = this.v(), i = n.slice(0);
                            return this.ya(), n.splice(0, n.length), this.xa(), i
                        }
                        return t ? this.remove(function (o) {
                            return 0 <= e.a.A(t, o)
                        }) : []
                    }, destroy: function (t) {
                        var n = this.v(), i = typeof t != "function" || e.O(t) ? function (c) {
                            return c === t
                        } : t;
                        this.ya();
                        for (var o = n.length - 1; 0 <= o; o--) {
                            var u = n[o];
                            i(u) && (u._destroy = !0)
                        }
                        this.xa()
                    }, destroyAll: function (t) {
                        return t === m ? this.destroy(function () {
                            return !0
                        }) : t ? this.destroy(function (n) {
                            return 0 <= e.a.A(t, n)
                        }) : []
                    }, indexOf: function (t) {
                        var n = this();
                        return e.a.A(n, t)
                    }, replace: function (t, n) {
                        var i = this.indexOf(t);
                        0 <= i && (this.ya(), this.v()[i] = n, this.xa())
                    }, sorted: function (t) {
                        var n = this().slice(0);
                        return t ? n.sort(t) : n.sort()
                    }, reversed: function () {
                        return this().slice(0).reverse()
                    }
                }, e.a.Ba && e.a.setPrototypeOf(e.Ha.fn, e.ta.fn), e.a.D("pop push reverse shift sort splice unshift".split(" "), function (t) {
                    e.Ha.fn[t] = function () {
                        var n = this.v();
                        this.ya(), this.zc(n, t, arguments);
                        var i = n[t].apply(n, arguments);
                        return this.xa(), i === n ? this : i
                    }
                }), e.a.D(["slice"], function (t) {
                    e.Ha.fn[t] = function () {
                        var n = this();
                        return n[t].apply(n, arguments)
                    }
                }), e.Pc = function (t) {
                    return e.O(t) && typeof t.remove == "function" && typeof t.push == "function"
                }, e.b("observableArray", e.Ha), e.b("isObservableArray", e.Pc), e.Ta.trackArrayChanges = function (t, n) {
                    function i() {
                        function d() {
                            if (f) {
                                var y = [].concat(t.v() || []), r;
                                t.Wa("arrayChange") && ((!u || 1 < f) && (u = e.a.Pb(s, y, t.Ob)), r = u), s = y, u = null, f = 0, r && r.length && t.notifySubscribers(r, "arrayChange")
                            }
                        }

                        o ? d() : (o = !0, l = t.subscribe(function () {
                            ++f
                        }, null, "spectate"), s = [].concat(t.v() || []), u = null, c = t.subscribe(d))
                    }

                    if (t.Ob = {}, n && typeof n == "object" && e.a.extend(t.Ob, n), t.Ob.sparse = !0, !t.zc) {
                        var o = !1, u = null, c, l, f = 0, s, a = t.Qa, h = t.hb;
                        t.Qa = function (d) {
                            a && a.call(t, d), d === "arrayChange" && i()
                        }, t.hb = function (d) {
                            h && h.call(t, d), d !== "arrayChange" || t.Wa("arrayChange") || (c && c.s(), l && l.s(), l = c = null, o = !1, s = m)
                        }, t.zc = function (d, y, r) {
                            function v(T, N, I) {
                                return p[p.length] = {status: T, value: N, index: I}
                            }

                            if (o && !f) {
                                var p = [], w = d.length, x = r.length, g = 0;
                                switch (y) {
                                    case"push":
                                        g = w;
                                    case"unshift":
                                        for (y = 0; y < x; y++) v("added", r[y], g + y);
                                        break;
                                    case"pop":
                                        g = w - 1;
                                    case"shift":
                                        w && v("deleted", d[g], g);
                                        break;
                                    case"splice":
                                        y = Math.min(Math.max(0, 0 > r[0] ? w + r[0] : r[0]), w);
                                        for (var w = x === 1 ? w : Math.min(y + (r[1] || 0), w), x = y + x - 2, g = Math.max(w, x), E = [], S = [], O = 2; y < g; ++y, ++O) y < w && S.push(v("deleted", d[y], y)), y < x && E.push(v("added", r[O], y));
                                        e.a.Kc(S, E);
                                        break;
                                    default:
                                        return
                                }
                                u = p
                            }
                        }
                    }
                };
                var M = e.a.Da("_state");
                e.o = e.$ = function (t, n, i) {
                    function o() {
                        if (0 < arguments.length) {
                            if (typeof u == "function") u.apply(c.nb, arguments); else throw Error("Cannot write a value to a ko.computed unless you specify a 'write' option. If you wish to read the current value, don't pass any parameters.");
                            return this
                        }
                        return c.ra || e.u.cc(o), (c.ka || c.J && o.Xa()) && o.ha(), c.X
                    }

                    if (typeof t == "object" ? i = t : (i = i || {}, t && (i.read = t)), typeof i.read != "function") throw Error("Pass a function that returns the value of the ko.computed");
                    var u = i.write, c = {
                        X: m,
                        sa: !0,
                        ka: !0,
                        rb: !1,
                        jc: !1,
                        ra: !1,
                        wb: !1,
                        J: !1,
                        Wc: i.read,
                        nb: n || i.owner,
                        l: i.disposeWhenNodeIsRemoved || i.l || null,
                        Sa: i.disposeWhen || i.Sa,
                        Rb: null,
                        I: {},
                        V: 0,
                        Ic: null
                    };
                    return o[M] = c, o.Nc = typeof u == "function", e.a.Ba || e.a.extend(o, e.T.fn), e.T.fn.qb(o), e.a.Ab(o, R), i.pure ? (c.wb = !0, c.J = !0, e.a.extend(o, be)) : i.deferEvaluation && e.a.extend(o, we), e.options.deferUpdates && e.Ta.deferred(o, !0), c.l && (c.jc = !0, c.l.nodeType || (c.l = null)), c.J || i.deferEvaluation || o.ha(), c.l && o.ja() && e.a.K.za(c.l, c.Rb = function () {
                        o.s()
                    }), o
                };
                var R = {
                    equalityComparer: ae, qa: function () {
                        return this[M].V
                    }, Va: function () {
                        var t = [];
                        return e.a.P(this[M].I, function (n, i) {
                            t[i.Ka] = i.da
                        }), t
                    }, Vb: function (t) {
                        if (!this[M].V) return !1;
                        var n = this.Va();
                        return e.a.A(n, t) !== -1 ? !0 : !!e.a.Lb(n, function (i) {
                            return i.Vb && i.Vb(t)
                        })
                    }, uc: function (t, n, i) {
                        if (this[M].wb && n === this) throw Error("A 'pure' computed must not be called recursively");
                        this[M].I[t] = i, i.Ka = this[M].V++, i.La = n.ob()
                    }, Xa: function () {
                        var t, n, i = this[M].I;
                        for (t in i) if (Object.prototype.hasOwnProperty.call(i, t) && (n = i[t], this.Ia && n.da.Ja || n.da.Dd(n.La))) return !0
                    }, Jd: function () {
                        this.Ia && !this[M].rb && this.Ia(!1)
                    }, ja: function () {
                        var t = this[M];
                        return t.ka || 0 < t.V
                    }, Rd: function () {
                        this.Ja ? this[M].ka && (this[M].sa = !0) : this.Hc()
                    }, $c: function (t) {
                        if (t.Hb) {
                            var n = t.subscribe(this.Jd, this, "dirty"), i = t.subscribe(this.Rd, this);
                            return {
                                da: t, s: function () {
                                    n.s(), i.s()
                                }
                            }
                        }
                        return t.subscribe(this.Hc, this)
                    }, Hc: function () {
                        var t = this, n = t.throttleEvaluation;
                        n && 0 <= n ? (clearTimeout(this[M].Ic), this[M].Ic = e.a.setTimeout(function () {
                            t.ha(!0)
                        }, n)) : t.Ia ? t.Ia(!0) : t.ha(!0)
                    }, ha: function (t) {
                        var n = this[M], i = n.Sa, o = !1;
                        if (!n.rb && !n.ra) {
                            if (n.l && !e.a.Sb(n.l) || i && i()) {
                                if (!n.jc) {
                                    this.s();
                                    return
                                }
                            } else n.jc = !1;
                            n.rb = !0;
                            try {
                                o = this.zd(t)
                            } finally {
                                n.rb = !1
                            }
                            return o
                        }
                    }, zd: function (t) {
                        var n = this[M], o = !1, i = n.wb ? m : !n.V, o = {qd: this, mb: n.I, Qb: n.V};
                        e.u.xc({pd: o, od: ge, o: this, Ya: i}), n.I = {}, n.V = 0;
                        var u = this.yd(n, o);
                        return n.V ? o = this.sb(n.X, u) : (this.s(), o = !0), o && (n.J ? this.Gb() : this.notifySubscribers(n.X, "beforeChange"), n.X = u, this.notifySubscribers(n.X, "spectate"), !n.J && t && this.notifySubscribers(n.X), this.rc && this.rc()), i && this.notifySubscribers(n.X, "awake"), o
                    }, yd: function (t, n) {
                        try {
                            var i = t.Wc;
                            return t.nb ? i.call(t.nb) : i()
                        } finally {
                            e.u.end(), n.Qb && !t.J && e.a.P(n.mb, me), t.sa = t.ka = !1
                        }
                    }, v: function (t) {
                        var n = this[M];
                        return (n.ka && (t || !n.V) || n.J && this.Xa()) && this.ha(), n.X
                    }, ub: function (t) {
                        e.T.fn.ub.call(this, t), this.nc = function () {
                            return this[M].J || (this[M].sa ? this.ha() : this[M].ka = !1), this[M].X
                        }, this.Ia = function (n) {
                            this.pc(this[M].X), this[M].ka = !0, n && (this[M].sa = !0), this.qc(this, !n)
                        }
                    }, s: function () {
                        var t = this[M];
                        !t.J && t.I && e.a.P(t.I, function (n, i) {
                            i.s && i.s()
                        }), t.l && t.Rb && e.a.K.yb(t.l, t.Rb), t.I = m, t.V = 0, t.ra = !0, t.sa = !1, t.ka = !1, t.J = !1, t.l = m, t.Sa = m, t.Wc = m, this.Nc || (t.nb = m)
                    }
                }, be = {
                    Qa: function (t) {
                        var n = this, i = n[M];
                        if (!i.ra && i.J && t == "change") {
                            if (i.J = !1, i.sa || n.Xa()) i.I = null, i.V = 0, n.ha() && n.Gb(); else {
                                var o = [];
                                e.a.P(i.I, function (u, c) {
                                    o[c.Ka] = u
                                }), e.a.D(o, function (u, c) {
                                    var l = i.I[u], f = n.$c(l.da);
                                    f.Ka = c, f.La = l.La, i.I[u] = f
                                }), n.Xa() && n.ha() && n.Gb()
                            }
                            i.ra || n.notifySubscribers(i.X, "awake")
                        }
                    }, hb: function (t) {
                        var n = this[M];
                        n.ra || t != "change" || this.Wa("change") || (e.a.P(n.I, function (i, o) {
                            o.s && (n.I[i] = {da: o.da, Ka: o.Ka, La: o.La}, o.s())
                        }), n.J = !0, this.notifySubscribers(m, "asleep"))
                    }, ob: function () {
                        var t = this[M];
                        return t.J && (t.sa || this.Xa()) && this.ha(), e.T.fn.ob.call(this)
                    }
                }, we = {
                    Qa: function (t) {
                        t != "change" && t != "beforeChange" || this.v()
                    }
                };
                e.a.Ba && e.a.setPrototypeOf(R, e.T.fn);
                var se = e.ta.Ma;
                R[se] = e.o, e.Oc = function (t) {
                    return typeof t == "function" && t[se] === R[se]
                }, e.Fd = function (t) {
                    return e.Oc(t) && t[M] && t[M].wb
                }, e.b("computed", e.o), e.b("dependentObservable", e.o), e.b("isComputed", e.Oc), e.b("isPureComputed", e.Fd), e.b("computed.fn", R), e.L(R, "peek", R.v), e.L(R, "dispose", R.s), e.L(R, "isActive", R.ja), e.L(R, "getDependenciesCount", R.qa), e.L(R, "getDependencies", R.Va), e.xb = function (t, n) {
                    return typeof t == "function" ? e.o(t, n, {pure: !0}) : (t = e.a.extend({}, t), t.pure = !0, e.o(t, n))
                }, e.b("pureComputed", e.xb), function () {
                    function t(o, u, c) {
                        if (c = c || new i, o = u(o), typeof o != "object" || o === null || o === m || o instanceof RegExp || o instanceof Date || o instanceof String || o instanceof Number || o instanceof Boolean) return o;
                        var l = o instanceof Array ? [] : {};
                        return c.save(o, l), n(o, function (f) {
                            var s = u(o[f]);
                            switch (typeof s) {
                                case"boolean":
                                case"number":
                                case"string":
                                case"function":
                                    l[f] = s;
                                    break;
                                case"object":
                                case"undefined":
                                    var a = c.get(s);
                                    l[f] = a !== m ? a : t(s, u, c)
                            }
                        }), l
                    }

                    function n(o, u) {
                        if (o instanceof Array) {
                            for (var c = 0; c < o.length; c++) u(c);
                            typeof o.toJSON == "function" && u("toJSON")
                        } else for (c in o) u(c)
                    }

                    function i() {
                        this.keys = [], this.values = []
                    }

                    e.ad = function (o) {
                        if (arguments.length == 0) throw Error("When calling ko.toJS, pass the object you want to convert.");
                        return t(o, function (u) {
                            for (var c = 0; e.O(u) && 10 > c; c++) u = u();
                            return u
                        })
                    }, e.toJSON = function (o, u, c) {
                        return o = e.ad(o), e.a.hc(o, u, c)
                    }, i.prototype = {
                        constructor: i, save: function (o, u) {
                            var c = e.a.A(this.keys, o);
                            0 <= c ? this.values[c] = u : (this.keys.push(o), this.values.push(u))
                        }, get: function (o) {
                            return o = e.a.A(this.keys, o), 0 <= o ? this.values[o] : m
                        }
                    }
                }(), e.b("toJS", e.ad), e.b("toJSON", e.toJSON), e.Wd = function (t, n, i) {
                    function o(u) {
                        var c = e.xb(t, i).extend({ma: "always"}), l = c.subscribe(function (f) {
                            f && (l.s(), u(f))
                        });
                        return c.notifySubscribers(c.v()), l
                    }

                    return typeof Promise != "function" || n ? o(n.bind(i)) : new Promise(o)
                }, e.b("when", e.Wd), function () {
                    e.w = {
                        M: function (t) {
                            switch (e.a.R(t)) {
                                case"option":
                                    return t.__ko__hasDomDataOptionValue__ === !0 ? e.a.g.get(t, e.c.options.$b) : 7 >= e.a.W ? t.getAttributeNode("value") && t.getAttributeNode("value").specified ? t.value : t.text : t.value;
                                case"select":
                                    return 0 <= t.selectedIndex ? e.w.M(t.options[t.selectedIndex]) : m;
                                default:
                                    return t.value
                            }
                        }, cb: function (t, n, i) {
                            switch (e.a.R(t)) {
                                case"option":
                                    typeof n == "string" ? (e.a.g.set(t, e.c.options.$b, m), "__ko__hasDomDataOptionValue__" in t && delete t.__ko__hasDomDataOptionValue__, t.value = n) : (e.a.g.set(t, e.c.options.$b, n), t.__ko__hasDomDataOptionValue__ = !0, t.value = typeof n == "number" ? n : "");
                                    break;
                                case"select":
                                    (n === "" || n === null) && (n = m);
                                    for (var o = -1, u = 0, c = t.options.length, l; u < c; ++u) if (l = e.w.M(t.options[u]), l == n || l === "" && n === m) {
                                        o = u;
                                        break
                                    }
                                    (i || 0 <= o || n === m && 1 < t.size) && (t.selectedIndex = o, e.a.W === 6 && e.a.setTimeout(function () {
                                        t.selectedIndex = o
                                    }, 0));
                                    break;
                                default:
                                    (n === null || n === m) && (n = ""), t.value = n
                            }
                        }
                    }
                }(), e.b("selectExtensions", e.w), e.b("selectExtensions.readValue", e.w.M), e.b("selectExtensions.writeValue", e.w.cb), e.m = function () {
                    function t(f) {
                        f = e.a.Db(f), f.charCodeAt(0) === 123 && (f = f.slice(1, -1)), f += `
,`;
                        var s = [], a = f.match(o), h, d = [], y = 0;
                        if (1 < a.length) {
                            for (var r = 0, v; v = a[r]; ++r) {
                                var p = v.charCodeAt(0);
                                if (p === 44) {
                                    if (0 >= y) {
                                        s.push(h && d.length ? {
                                            key: h,
                                            value: d.join("")
                                        } : {unknown: h || d.join("")}), h = y = 0, d = [];
                                        continue
                                    }
                                } else if (p === 58) {
                                    if (!y && !h && d.length === 1) {
                                        h = d.pop();
                                        continue
                                    }
                                } else {
                                    if (p === 47 && 1 < v.length && (v.charCodeAt(1) === 47 || v.charCodeAt(1) === 42)) continue;
                                    p === 47 && r && 1 < v.length ? (p = a[r - 1].match(u)) && !c[p[0]] && (f = f.substr(f.indexOf(v) + 1), a = f.match(o), r = -1, v = "/") : p === 40 || p === 123 || p === 91 ? ++y : p === 41 || p === 125 || p === 93 ? --y : h || d.length || p !== 34 && p !== 39 || (v = v.slice(1, -1))
                                }
                                d.push(v)
                            }
                            if (0 < y) throw Error("Unbalanced parentheses, braces, or brackets")
                        }
                        return s
                    }

                    var n = ["true", "false", "null", "undefined"],
                        i = /^(?:[$_a-z][$\w]*|(.+)(\.\s*[$_a-z][$\w]*|\[.+\]))$/i, o = RegExp(`"(?:\\\\.|[^"])*"|'(?:\\\\.|[^'])*'|\`(?:\\\\.|[^\`])*\`|/\\*(?:[^*]|\\*+[^*/])*\\*+/|//.*
|/(?:\\\\.|[^/])+/w*|[^\\s:,/][^,"'\`{}()/:[\\]]*[^\\s,"'\`{}()/:[\\]]|[^\\s]`, "g"), u = /[\])"'A-Za-z0-9_$]+$/,
                        c = {in: 1, return: 1, typeof: 1}, l = {};
                    return {
                        Ra: [], wa: l, ac: t, vb: function (f, s) {
                            function a(p, w) {
                                var x;
                                if (!r) {
                                    var g = e.getBindingHandler(p);
                                    if (g && g.preprocess && !(w = g.preprocess(w, p, a))) return;
                                    (g = l[p]) && (x = w, 0 <= e.a.A(n, x) ? x = !1 : (g = x.match(i), x = g === null ? !1 : g[1] ? "Object(" + g[1] + ")" + g[2] : x), g = x), g && d.push("'" + (typeof l[p] == "string" ? l[p] : p) + "':function(_z){" + x + "=_z}")
                                }
                                y && (w = "function(){return " + w + " }"), h.push("'" + p + "':" + w)
                            }

                            s = s || {};
                            var h = [], d = [], y = s.valueAccessors, r = s.bindingParams,
                                v = typeof f == "string" ? t(f) : f;
                            return e.a.D(v, function (p) {
                                a(p.key || p.unknown, p.value)
                            }), d.length && a("_ko_property_writers", "{" + d.join(",") + " }"), h.join(",")
                        }, Id: function (f, s) {
                            for (var a = 0; a < f.length; a++) if (f[a].key == s) return !0;
                            return !1
                        }, eb: function (f, s, a, h, d) {
                            f && e.O(f) ? !e.Za(f) || d && f.v() === h || f(h) : (f = s.get("_ko_property_writers")) && f[a] && f[a](h)
                        }
                    }
                }(), e.b("expressionRewriting", e.m), e.b("expressionRewriting.bindingRewriteValidators", e.m.Ra), e.b("expressionRewriting.parseObjectLiteral", e.m.ac), e.b("expressionRewriting.preProcessBindings", e.m.vb), e.b("expressionRewriting._twoWayBindings", e.m.wa), e.b("jsonExpressionRewriting", e.m), e.b("jsonExpressionRewriting.insertPropertyAccessorsIntoJson", e.m.vb), function () {
                    function t(a) {
                        return a.nodeType == 8 && c.test(u ? a.text : a.nodeValue)
                    }

                    function n(a) {
                        return a.nodeType == 8 && l.test(u ? a.text : a.nodeValue)
                    }

                    function i(a, h) {
                        for (var d = a, y = 1, r = []; d = d.nextSibling;) {
                            if (n(d) && (e.a.g.set(d, s, !0), y--, y === 0)) return r;
                            r.push(d), t(d) && y++
                        }
                        if (!h) throw Error("Cannot find closing comment tag to match: " + a.nodeValue);
                        return null
                    }

                    function o(a, h) {
                        var d = i(a, h);
                        return d ? 0 < d.length ? d[d.length - 1].nextSibling : a.nextSibling : null
                    }

                    var u = D && D.createComment("test").text === "<!--test-->",
                        c = u ? /^\x3c!--\s*ko(?:\s+([\s\S]+))?\s*--\x3e$/ : /^\s*ko(?:\s+([\s\S]+))?\s*$/,
                        l = u ? /^\x3c!--\s*\/ko\s*--\x3e$/ : /^\s*\/ko\s*$/, f = {ul: !0, ol: !0},
                        s = "__ko_matchedEndComment__";
                    e.h = {
                        ea: {}, childNodes: function (a) {
                            return t(a) ? i(a) : a.childNodes
                        }, Ea: function (a) {
                            if (t(a)) {
                                a = e.h.childNodes(a);
                                for (var h = 0, d = a.length; h < d; h++) e.removeNode(a[h])
                            } else e.a.Tb(a)
                        }, va: function (a, h) {
                            if (t(a)) {
                                e.h.Ea(a);
                                for (var d = a.nextSibling, y = 0, r = h.length; y < r; y++) d.parentNode.insertBefore(h[y], d)
                            } else e.a.va(a, h)
                        }, Vc: function (a, h) {
                            var d;
                            t(a) ? (d = a.nextSibling, a = a.parentNode) : d = a.firstChild, d ? h !== d && a.insertBefore(h, d) : a.appendChild(h)
                        }, Wb: function (a, h, d) {
                            d ? (d = d.nextSibling, t(a) && (a = a.parentNode), d ? h !== d && a.insertBefore(h, d) : a.appendChild(h)) : e.h.Vc(a, h)
                        }, firstChild: function (a) {
                            if (t(a)) return !a.nextSibling || n(a.nextSibling) ? null : a.nextSibling;
                            if (a.firstChild && n(a.firstChild)) throw Error("Found invalid end comment, as the first child of " + a);
                            return a.firstChild
                        }, nextSibling: function (a) {
                            if (t(a) && (a = o(a)), a.nextSibling && n(a.nextSibling)) {
                                var h = a.nextSibling;
                                if (n(h) && !e.a.g.get(h, s)) throw Error("Found end comment without a matching opening comment, as child of " + a);
                                return null
                            }
                            return a.nextSibling
                        }, Cd: t, Vd: function (a) {
                            return (a = (u ? a.text : a.nodeValue).match(c)) ? a[1] : null
                        }, Sc: function (a) {
                            if (f[e.a.R(a)]) {
                                var h = a.firstChild;
                                if (h) do if (h.nodeType === 1) {
                                    var d;
                                    d = h.firstChild;
                                    var y = null;
                                    if (d) do if (y) y.push(d); else if (t(d)) {
                                        var r = o(d, !0);
                                        r ? d = r : y = [d]
                                    } else n(d) && (y = [d]); while (d = d.nextSibling);
                                    if (d = y) for (y = h.nextSibling, r = 0; r < d.length; r++) y ? a.insertBefore(d[r], y) : a.appendChild(d[r])
                                } while (h = h.nextSibling)
                            }
                        }
                    }
                }(), e.b("virtualElements", e.h), e.b("virtualElements.allowedBindings", e.h.ea), e.b("virtualElements.emptyNode", e.h.Ea), e.b("virtualElements.insertAfter", e.h.Wb), e.b("virtualElements.prepend", e.h.Vc), e.b("virtualElements.setDomNodeChildren", e.h.va), function () {
                    e.ga = function () {
                        this.nd = {}
                    }, e.a.extend(e.ga.prototype, {
                        nodeHasBindings: function (t) {
                            switch (t.nodeType) {
                                case 1:
                                    return t.getAttribute("data-bind") != null || e.j.getComponentNameForNode(t);
                                case 8:
                                    return e.h.Cd(t);
                                default:
                                    return !1
                            }
                        }, getBindings: function (t, n) {
                            var i = this.getBindingsString(t, n), i = i ? this.parseBindingsString(i, n, t) : null;
                            return e.j.tc(i, t, n, !1)
                        }, getBindingAccessors: function (t, n) {
                            var i = this.getBindingsString(t, n),
                                i = i ? this.parseBindingsString(i, n, t, {valueAccessors: !0}) : null;
                            return e.j.tc(i, t, n, !0)
                        }, getBindingsString: function (t) {
                            switch (t.nodeType) {
                                case 1:
                                    return t.getAttribute("data-bind");
                                case 8:
                                    return e.h.Vd(t);
                                default:
                                    return null
                            }
                        }, parseBindingsString: function (t, n, i, o) {
                            try {
                                var u = this.nd, c = t + (o && o.valueAccessors || ""), l;
                                if (!(l = u[c])) {
                                    var f, s = "with($context){with($data||{}){return{" + e.m.vb(t, o) + "}}}";
                                    f = new Function("$context", "$element", s), l = u[c] = f
                                }
                                return l(n, i)
                            } catch (a) {
                                throw a.message = `Unable to parse bindings.
Bindings value: ` + t + `
Message: ` + a.message, a
                            }
                        }
                    }), e.ga.instance = new e.ga
                }(), e.b("bindingProvider", e.ga), function () {
                    function t(g) {
                        var E = (g = e.a.g.get(g, x)) && g.N;
                        E && (g.N = null, E.Tc())
                    }

                    function n(g, E, S) {
                        this.node = g, this.yc = E, this.kb = [], this.H = !1, E.N || e.a.K.za(g, t), S && S.N && (S.N.kb.push(g), this.Kb = S)
                    }

                    function i(g) {
                        return function () {
                            return g
                        }
                    }

                    function o(g) {
                        return g()
                    }

                    function u(g) {
                        return e.a.Ga(e.u.G(g), function (E, S) {
                            return function () {
                                return g()[S]
                            }
                        })
                    }

                    function c(g, E, S) {
                        return typeof g == "function" ? u(g.bind(null, E, S)) : e.a.Ga(g, i)
                    }

                    function l(g, E) {
                        return u(this.getBindings.bind(this, g, E))
                    }

                    function f(g, E) {
                        var S = e.h.firstChild(E);
                        if (S) {
                            var O, T = e.ga.instance, N = T.preprocessNode;
                            if (N) {
                                for (; O = S;) S = e.h.nextSibling(O), N.call(T, O);
                                S = e.h.firstChild(E)
                            }
                            for (; O = S;) S = e.h.nextSibling(O), s(g, O)
                        }
                        e.i.ma(E, e.i.H)
                    }

                    function s(g, E) {
                        var S = g, O = E.nodeType === 1;
                        O && e.h.Sc(E), (O || e.ga.instance.nodeHasBindings(E)) && (S = h(E, null, g).bindingContextForDescendants), S && !p[e.a.R(E)] && f(S, E)
                    }

                    function a(g) {
                        var E = [], S = {}, O = [];
                        return e.a.P(g, function T(N) {
                            if (!S[N]) {
                                var I = e.getBindingHandler(N);
                                I && (I.after && (O.push(N), e.a.D(I.after, function (F) {
                                    if (g[F]) {
                                        if (e.a.A(O, F) !== -1) throw Error("Cannot combine the following bindings, because they have a cyclic dependency: " + O.join(", "));
                                        T(F)
                                    }
                                }), O.length--), E.push({key: N, Mc: I})), S[N] = !0
                            }
                        }), E
                    }

                    function h(g, E, S) {
                        var O = e.a.g.Ub(g, x, {}), T = O.hd;
                        if (!E) {
                            if (T) throw Error("You cannot apply bindings multiple times to the same element.");
                            O.hd = !0
                        }
                        T || (O.context = S), O.Zb || (O.Zb = {});
                        var N;
                        if (E && typeof E != "function") N = E; else {
                            var I = e.ga.instance, F = I.getBindingAccessors || l, $ = e.$(function () {
                                return (N = E ? E(S, g) : F.call(I, g, S)) && (S[y] && S[y](), S[v] && S[v]()), N
                            }, null, {l: g});
                            N && $.ja() || ($ = null)
                        }
                        var H = S, j;
                        if (N) {
                            var q = function () {
                                return e.a.Ga($ ? $() : N, o)
                            }, J = $ ? function (L) {
                                return function () {
                                    return o($()[L])
                                }
                            } : function (L) {
                                return N[L]
                            };
                            q.get = function (L) {
                                return N[L] && o(J(L))
                            }, q.has = function (L) {
                                return L in N
                            }, e.i.H in N && e.i.subscribe(g, e.i.H, function () {
                                var L = (0, N[e.i.H])();
                                if (L) {
                                    var K = e.h.childNodes(g);
                                    K.length && L(K, e.Ec(K[0]))
                                }
                            }), e.i.pa in N && (H = e.i.Cb(g, S), e.i.subscribe(g, e.i.pa, function () {
                                var L = (0, N[e.i.pa])();
                                L && e.h.firstChild(g) && L(g)
                            })), O = a(N), e.a.D(O, function (L) {
                                var K = L.Mc.init, Z = L.Mc.update, z = L.key;
                                if (g.nodeType === 8 && !e.h.ea[z]) throw Error("The binding '" + z + "' cannot be used with virtual elements");
                                try {
                                    typeof K == "function" && e.u.G(function () {
                                        var X = K(g, J(z), q, H.$data, H);
                                        if (X && X.controlsDescendantBindings) {
                                            if (j !== m) throw Error("Multiple bindings (" + j + " and " + z + ") are trying to control descendant bindings of the same element. You cannot use these bindings together on the same element.");
                                            j = z
                                        }
                                    }), typeof Z == "function" && e.$(function () {
                                        Z(g, J(z), q, H.$data, H)
                                    }, null, {l: g})
                                } catch (X) {
                                    throw X.message = 'Unable to process binding "' + z + ": " + N[z] + `"
Message: ` + X.message, X
                                }
                            })
                        }
                        return O = j === m, {shouldBindDescendants: O, bindingContextForDescendants: O && H}
                    }

                    function d(g, E) {
                        return g && g instanceof e.fa ? g : new e.fa(g, m, m, E)
                    }

                    var y = e.a.Da("_subscribable"), r = e.a.Da("_ancestorBindingInfo"), v = e.a.Da("_dataDependency");
                    e.c = {};
                    var p = {script: !0, textarea: !0, template: !0};
                    e.getBindingHandler = function (g) {
                        return e.c[g]
                    };
                    var w = {};
                    e.fa = function (g, E, S, O, T) {
                        function N() {
                            var J = H ? $() : $, L = e.a.f(J);
                            return E ? (e.a.extend(I, E), r in E && (I[r] = E[r])) : (I.$parents = [], I.$root = L, I.ko = e), I[y] = j, F ? L = I.$data : (I.$rawData = J, I.$data = L), S && (I[S] = L), O && O(I, E, L), E && E[y] && !e.S.o().Vb(E[y]) && E[y](), q && (I[v] = q), I.$data
                        }

                        var I = this, F = g === w, $ = F ? m : g, H = typeof $ == "function" && !e.O($), j,
                            q = T && T.dataDependency;
                        T && T.exportDependencies ? N() : (j = e.xb(N), j.v(), j.ja() ? j.equalityComparer = null : I[y] = m)
                    }, e.fa.prototype.createChildContext = function (g, E, S, O) {
                        if (!O && E && typeof E == "object" && (O = E, E = O.as, S = O.extend), E && O && O.noChildContext) {
                            var T = typeof g == "function" && !e.O(g);
                            return new e.fa(w, this, null, function (N) {
                                S && S(N), N[E] = T ? g() : g
                            }, O)
                        }
                        return new e.fa(g, this, E, function (N, I) {
                            N.$parentContext = I, N.$parent = I.$data, N.$parents = (I.$parents || []).slice(0), N.$parents.unshift(N.$parent), S && S(N)
                        }, O)
                    }, e.fa.prototype.extend = function (g, E) {
                        return new e.fa(w, this, null, function (S) {
                            e.a.extend(S, typeof g == "function" ? g(S) : g)
                        }, E)
                    };
                    var x = e.a.g.Z();
                    n.prototype.Tc = function () {
                        this.Kb && this.Kb.N && this.Kb.N.sd(this.node)
                    }, n.prototype.sd = function (g) {
                        e.a.Pa(this.kb, g), !this.kb.length && this.H && this.Cc()
                    }, n.prototype.Cc = function () {
                        this.H = !0, this.yc.N && !this.kb.length && (this.yc.N = null, e.a.K.yb(this.node, t), e.i.ma(this.node, e.i.pa), this.Tc())
                    }, e.i = {
                        H: "childrenComplete", pa: "descendantsComplete", subscribe: function (g, E, S, O, T) {
                            var N = e.a.g.Ub(g, x, {});
                            return N.Fa || (N.Fa = new e.T), T && T.notifyImmediately && N.Zb[E] && e.u.G(S, O, [g]), N.Fa.subscribe(S, O, E)
                        }, ma: function (g, E) {
                            var S = e.a.g.get(g, x);
                            if (S && (S.Zb[E] = !0, S.Fa && S.Fa.notifySubscribers(g, E), E == e.i.H)) {
                                if (S.N) S.N.Cc(); else if (S.N === m && S.Fa && S.Fa.Wa(e.i.pa)) throw Error("descendantsComplete event not supported for bindings on this node")
                            }
                        }, Cb: function (g, E) {
                            var S = e.a.g.Ub(g, x, {});
                            return S.N || (S.N = new n(g, S, E[r])), E[r] == S ? E : E.extend(function (O) {
                                O[r] = S
                            })
                        }
                    }, e.Td = function (g) {
                        return (g = e.a.g.get(g, x)) && g.context
                    }, e.ib = function (g, E, S) {
                        return g.nodeType === 1 && e.h.Sc(g), h(g, E, d(S))
                    }, e.ld = function (g, E, S) {
                        return S = d(S), e.ib(g, c(E, S, g), S)
                    }, e.Oa = function (g, E) {
                        E.nodeType !== 1 && E.nodeType !== 8 || f(d(g), E)
                    }, e.vc = function (g, E, S) {
                        if (!B && C.jQuery && (B = C.jQuery), 2 > arguments.length) {
                            if (E = D.body, !E) throw Error("ko.applyBindings: could not find document.body; has the document been loaded?")
                        } else if (!E || E.nodeType !== 1 && E.nodeType !== 8) throw Error("ko.applyBindings: first parameter should be your view model; second parameter should be a DOM node");
                        s(d(g, S), E)
                    }, e.Dc = function (g) {
                        return !g || g.nodeType !== 1 && g.nodeType !== 8 ? m : e.Td(g)
                    }, e.Ec = function (g) {
                        return (g = e.Dc(g)) ? g.$data : m
                    }, e.b("bindingHandlers", e.c), e.b("bindingEvent", e.i), e.b("bindingEvent.subscribe", e.i.subscribe), e.b("bindingEvent.startPossiblyAsyncContentBinding", e.i.Cb), e.b("applyBindings", e.vc), e.b("applyBindingsToDescendants", e.Oa), e.b("applyBindingAccessorsToNode", e.ib), e.b("applyBindingsToNode", e.ld), e.b("contextFor", e.Dc), e.b("dataFor", e.Ec)
                }(), function (t) {
                    function n(l, f) {
                        var s = Object.prototype.hasOwnProperty.call(u, l) ? u[l] : t, a;
                        s ? s.subscribe(f) : (s = u[l] = new e.T, s.subscribe(f), i(l, function (h, d) {
                            var y = !(!d || !d.synchronous);
                            c[l] = {
                                definition: h,
                                Gd: y
                            }, delete u[l], a || y ? s.notifySubscribers(h) : e.na.zb(function () {
                                s.notifySubscribers(h)
                            })
                        }), a = !0)
                    }

                    function i(l, f) {
                        o("getConfig", [l], function (s) {
                            s ? o("loadComponent", [l, s], function (a) {
                                f(a, s)
                            }) : f(null, null)
                        })
                    }

                    function o(l, f, s, a) {
                        a || (a = e.j.loaders.slice(0));
                        var h = a.shift();
                        if (h) {
                            var d = h[l];
                            if (d) {
                                var y = !1;
                                if (d.apply(h, f.concat(function (r) {
                                    y ? s(null) : r !== null ? s(r) : o(l, f, s, a)
                                })) !== t && (y = !0, !h.suppressLoaderExceptions)) throw Error("Component loaders must supply values by invoking the callback, not by returning values synchronously.")
                            } else o(l, f, s, a)
                        } else s(null)
                    }

                    var u = {}, c = {};
                    e.j = {
                        get: function (l, f) {
                            var s = Object.prototype.hasOwnProperty.call(c, l) ? c[l] : t;
                            s ? s.Gd ? e.u.G(function () {
                                f(s.definition)
                            }) : e.na.zb(function () {
                                f(s.definition)
                            }) : n(l, f)
                        }, Bc: function (l) {
                            delete c[l]
                        }, oc: o
                    }, e.j.loaders = [], e.b("components", e.j), e.b("components.get", e.j.get), e.b("components.clearCachedDefinition", e.j.Bc)
                }(), function () {
                    function t(s, a, h, d) {
                        function y() {
                            --v === 0 && d(r)
                        }

                        var r = {}, v = 2, p = h.template;
                        h = h.viewModel, p ? u(a, p, function (w) {
                            e.j.oc("loadTemplate", [s, w], function (x) {
                                r.template = x, y()
                            })
                        }) : y(), h ? u(a, h, function (w) {
                            e.j.oc("loadViewModel", [s, w], function (x) {
                                r[f] = x, y()
                            })
                        }) : y()
                    }

                    function n(s, a, h) {
                        if (typeof a == "function") h(function (y) {
                            return new a(y)
                        }); else if (typeof a[f] == "function") h(a[f]); else if ("instance" in a) {
                            var d = a.instance;
                            h(function () {
                                return d
                            })
                        } else "viewModel" in a ? n(s, a.viewModel, h) : s("Unknown viewModel value: " + a)
                    }

                    function i(s) {
                        switch (e.a.R(s)) {
                            case"script":
                                return e.a.ua(s.text);
                            case"textarea":
                                return e.a.ua(s.value);
                            case"template":
                                if (o(s.content)) return e.a.Ca(s.content.childNodes)
                        }
                        return e.a.Ca(s.childNodes)
                    }

                    function o(s) {
                        return C.DocumentFragment ? s instanceof DocumentFragment : s && s.nodeType === 11
                    }

                    function u(s, a, h) {
                        typeof a.require == "string" ? ce || C.require ? (ce || C.require)([a.require], function (d) {
                            d && typeof d == "object" && d.Xd && d.default && (d = d.default), h(d)
                        }) : s("Uses require, but no AMD loader is present") : h(a)
                    }

                    function c(s) {
                        return function (a) {
                            throw Error("Component '" + s + "': " + a)
                        }
                    }

                    var l = {};
                    e.j.register = function (s, a) {
                        if (!a) throw Error("Invalid configuration for " + s);
                        if (e.j.tb(s)) throw Error("Component " + s + " is already registered");
                        l[s] = a
                    }, e.j.tb = function (s) {
                        return Object.prototype.hasOwnProperty.call(l, s)
                    }, e.j.unregister = function (s) {
                        delete l[s], e.j.Bc(s)
                    }, e.j.Fc = {
                        getConfig: function (s, a) {
                            a(e.j.tb(s) ? l[s] : null)
                        }, loadComponent: function (s, a, h) {
                            var d = c(s);
                            u(d, a, function (y) {
                                t(s, d, y, h)
                            })
                        }, loadTemplate: function (s, a, h) {
                            if (s = c(s), typeof a == "string") h(e.a.ua(a)); else if (a instanceof Array) h(a); else if (o(a)) h(e.a.la(a.childNodes)); else if (a.element) if (a = a.element, C.HTMLElement ? a instanceof HTMLElement : a && a.tagName && a.nodeType === 1) h(i(a)); else if (typeof a == "string") {
                                var d = D.getElementById(a);
                                d ? h(i(d)) : s("Cannot find element with ID " + a)
                            } else s("Unknown element type: " + a); else s("Unknown template value: " + a)
                        }, loadViewModel: function (s, a, h) {
                            n(c(s), a, h)
                        }
                    };
                    var f = "createViewModel";
                    e.b("components.register", e.j.register), e.b("components.isRegistered", e.j.tb), e.b("components.unregister", e.j.unregister), e.b("components.defaultLoader", e.j.Fc), e.j.loaders.push(e.j.Fc), e.j.dd = l
                }(), function () {
                    function t(i, o) {
                        var u = i.getAttribute("params");
                        if (u) {
                            var u = n.parseBindingsString(u, o, i, {valueAccessors: !0, bindingParams: !0}),
                                u = e.a.Ga(u, function (f) {
                                    return e.o(f, null, {l: i})
                                }), c = e.a.Ga(u, function (f) {
                                    var s = f.v();
                                    return f.ja() ? e.o({
                                        read: function () {
                                            return e.a.f(f())
                                        }, write: e.Za(s) && function (a) {
                                            f()(a)
                                        }, l: i
                                    }) : s
                                });
                            return Object.prototype.hasOwnProperty.call(c, "$raw") || (c.$raw = u), c
                        }
                        return {$raw: {}}
                    }

                    e.j.getComponentNameForNode = function (i) {
                        var o = e.a.R(i);
                        if (e.j.tb(o) && (o.indexOf("-") != -1 || "" + i == "[object HTMLUnknownElement]" || 8 >= e.a.W && i.tagName === o)) return o
                    }, e.j.tc = function (i, o, u, c) {
                        if (o.nodeType === 1) {
                            var l = e.j.getComponentNameForNode(o);
                            if (l) {
                                if (i = i || {}, i.component) throw Error('Cannot use the "component" binding on a custom element matching a component');
                                var f = {name: l, params: t(o, u)};
                                i.component = c ? function () {
                                    return f
                                } : f
                            }
                        }
                        return i
                    };
                    var n = new e.ga;
                    9 > e.a.W && (e.j.register = function (i) {
                        return function (o) {
                            return i.apply(this, arguments)
                        }
                    }(e.j.register), D.createDocumentFragment = function (i) {
                        return function () {
                            var o = i();
                            return e.j.dd, o
                        }
                    }(D.createDocumentFragment))
                }(), function () {
                    function t(o, u, c) {
                        if (u = u.template, !u) throw Error("Component '" + o + "' has no template");
                        o = e.a.Ca(u), e.h.va(c, o)
                    }

                    function n(o, u, c) {
                        var l = o.createViewModel;
                        return l ? l.call(o, u, c) : u
                    }

                    var i = 0;
                    e.c.component = {
                        init: function (o, u, c, l, f) {
                            function s() {
                                var r = a && a.dispose;
                                typeof r == "function" && r.call(a), d && d.s(), h = a = d = null
                            }

                            var a, h, d, y = e.a.la(e.h.childNodes(o));
                            return e.h.Ea(o), e.a.K.za(o, s), e.o(function () {
                                var r = e.a.f(u()), v, p;
                                if (typeof r == "string" ? v = r : (v = e.a.f(r.name), p = e.a.f(r.params)), !v) throw Error("No component name specified");
                                var w = e.i.Cb(o, f), x = h = ++i;
                                e.j.get(v, function (g) {
                                    if (h === x) {
                                        if (s(), !g) throw Error("Unknown component '" + v + "'");
                                        t(v, g, o);
                                        var E = n(g, p, {element: o, templateNodes: y});
                                        g = w.createChildContext(E, {
                                            extend: function (S) {
                                                S.$component = E, S.$componentTemplateNodes = y
                                            }
                                        }), E && E.koDescendantsComplete && (d = e.i.subscribe(o, e.i.pa, E.koDescendantsComplete, E)), a = E, e.Oa(g, o)
                                    }
                                })
                            }, null, {l: o}), {controlsDescendantBindings: !0}
                        }
                    }, e.h.ea.component = !0
                }();
                var fe = {class: "className", for: "htmlFor"};
                e.c.attr = {
                    update: function (t, n) {
                        var i = e.a.f(n()) || {};
                        e.a.P(i, function (o, u) {
                            u = e.a.f(u);
                            var c = o.indexOf(":"),
                                c = "lookupNamespaceURI" in t && 0 < c && t.lookupNamespaceURI(o.substr(0, c)),
                                l = u === !1 || u === null || u === m;
                            l ? c ? t.removeAttributeNS(c, o) : t.removeAttribute(o) : u = u.toString(), 8 >= e.a.W && o in fe ? (o = fe[o], l ? t.removeAttribute(o) : t[o] = u) : l || (c ? t.setAttributeNS(c, o, u) : t.setAttribute(o, u)), o === "name" && e.a.Yc(t, l ? "" : u)
                        })
                    }
                }, function () {
                    e.c.checked = {
                        after: ["value", "attr"], init: function (t, n, i) {
                            function o() {
                                var r = t.checked, v = c();
                                if (!e.S.Ya() && (r || !f && !e.S.qa())) {
                                    var p = e.u.G(n);
                                    if (a) {
                                        var w = h ? p.v() : p, x = y;
                                        y = v, x !== v ? r && (e.a.Na(w, v, !0), e.a.Na(w, x, !1)) : e.a.Na(w, v, r), h && e.Za(p) && p(w)
                                    } else l && (v === m ? v = r : r || (v = m)), e.m.eb(p, i, "checked", v, !0)
                                }
                            }

                            function u() {
                                var r = e.a.f(n()), v = c();
                                a ? (t.checked = 0 <= e.a.A(r, v), y = v) : t.checked = l && v === m ? !!r : c() === r
                            }

                            var c = e.xb(function () {
                                if (i.has("checkedValue")) return e.a.f(i.get("checkedValue"));
                                if (d) return i.has("value") ? e.a.f(i.get("value")) : t.value
                            }), l = t.type == "checkbox", f = t.type == "radio";
                            if (l || f) {
                                var s = n(), a = l && e.a.f(s) instanceof Array, h = !(a && s.push && s.splice),
                                    d = f || a, y = a ? c() : m;
                                f && !t.name && e.c.uniqueName.init(t, function () {
                                    return !0
                                }), e.o(o, null, {l: t}), e.a.B(t, "click", o), e.o(u, null, {l: t}), s = m
                            }
                        }
                    }, e.m.wa.checked = !0, e.c.checkedValue = {
                        update: function (t, n) {
                            t.value = e.a.f(n())
                        }
                    }
                }(), e.c.class = {
                    update: function (t, n) {
                        var i = e.a.Db(e.a.f(n()));
                        e.a.Eb(t, t.__ko__cssValue, !1), t.__ko__cssValue = i, e.a.Eb(t, i, !0)
                    }
                }, e.c.css = {
                    update: function (t, n) {
                        var i = e.a.f(n());
                        i !== null && typeof i == "object" ? e.a.P(i, function (o, u) {
                            u = e.a.f(u), e.a.Eb(t, o, u)
                        }) : e.c.class.update(t, n)
                    }
                }, e.c.enable = {
                    update: function (t, n) {
                        var i = e.a.f(n());
                        i && t.disabled ? t.removeAttribute("disabled") : i || t.disabled || (t.disabled = !0)
                    }
                }, e.c.disable = {
                    update: function (t, n) {
                        e.c.enable.update(t, function () {
                            return !e.a.f(n())
                        })
                    }
                }, e.c.event = {
                    init: function (t, n, i, o, u) {
                        var c = n() || {};
                        e.a.P(c, function (l) {
                            typeof l == "string" && e.a.B(t, l, function (f) {
                                var s, a = n()[l];
                                if (a) {
                                    try {
                                        var h = e.a.la(arguments);
                                        o = u.$data, h.unshift(o), s = a.apply(o, h)
                                    } finally {
                                        s !== !0 && (f.preventDefault ? f.preventDefault() : f.returnValue = !1)
                                    }
                                    i.get(l + "Bubble") === !1 && (f.cancelBubble = !0, f.stopPropagation && f.stopPropagation())
                                }
                            })
                        })
                    }
                }, e.c.foreach = {
                    Rc: function (t) {
                        return function () {
                            var n = t(), i = e.a.bc(n);
                            return !i || typeof i.length == "number" ? {
                                foreach: n,
                                templateEngine: e.ba.Ma
                            } : (e.a.f(n), {
                                foreach: i.data,
                                as: i.as,
                                noChildContext: i.noChildContext,
                                includeDestroyed: i.includeDestroyed,
                                afterAdd: i.afterAdd,
                                beforeRemove: i.beforeRemove,
                                afterRender: i.afterRender,
                                beforeMove: i.beforeMove,
                                afterMove: i.afterMove,
                                templateEngine: e.ba.Ma
                            })
                        }
                    }, init: function (t, n) {
                        return e.c.template.init(t, e.c.foreach.Rc(n))
                    }, update: function (t, n, i, o, u) {
                        return e.c.template.update(t, e.c.foreach.Rc(n), i, o, u)
                    }
                }, e.m.Ra.foreach = !1, e.h.ea.foreach = !0, e.c.hasfocus = {
                    init: function (t, n, i) {
                        function o(l) {
                            t.__ko_hasfocusUpdating = !0;
                            var f = t.ownerDocument;
                            if ("activeElement" in f) {
                                var s;
                                try {
                                    s = f.activeElement
                                } catch {
                                    s = f.body
                                }
                                l = s === t
                            }
                            f = n(), e.m.eb(f, i, "hasfocus", l, !0), t.__ko_hasfocusLastValue = l, t.__ko_hasfocusUpdating = !1
                        }

                        var u = o.bind(null, !0), c = o.bind(null, !1);
                        e.a.B(t, "focus", u), e.a.B(t, "focusin", u), e.a.B(t, "blur", c), e.a.B(t, "focusout", c), t.__ko_hasfocusLastValue = !1
                    }, update: function (t, n) {
                        var i = !!e.a.f(n());
                        t.__ko_hasfocusUpdating || t.__ko_hasfocusLastValue === i || (i ? t.focus() : t.blur(), !i && t.__ko_hasfocusLastValue && t.ownerDocument.body.focus(), e.u.G(e.a.Fb, null, [t, i ? "focusin" : "focusout"]))
                    }
                }, e.m.wa.hasfocus = !0, e.c.hasFocus = e.c.hasfocus, e.m.wa.hasFocus = "hasfocus", e.c.html = {
                    init: function () {
                        return {controlsDescendantBindings: !0}
                    }, update: function (t, n) {
                        e.a.fc(t, n())
                    }
                }, function () {
                    function t(n, i, o) {
                        e.c[n] = {
                            init: function (u, c, l, f, s) {
                                var a, h, d = {}, y, r, v;
                                if (i) {
                                    f = l.get("as");
                                    var p = l.get("noChildContext");
                                    v = !(f && p), d = {as: f, noChildContext: p, exportDependencies: v}
                                }
                                return r = (y = l.get("completeOn") == "render") || l.has(e.i.pa), e.o(function () {
                                    var w = e.a.f(c()), x = !o != !w, g = !h, E;
                                    (v || x !== a) && (r && (s = e.i.Cb(u, s)), x && ((!i || v) && (d.dataDependency = e.S.o()), E = i ? s.createChildContext(typeof w == "function" ? w : c, d) : e.S.qa() ? s.extend(null, d) : s), g && e.S.qa() && (h = e.a.Ca(e.h.childNodes(u), !0)), x ? (g || e.h.va(u, e.a.Ca(h)), e.Oa(E, u)) : (e.h.Ea(u), y || e.i.ma(u, e.i.H)), a = x)
                                }, null, {l: u}), {controlsDescendantBindings: !0}
                            }
                        }, e.m.Ra[n] = !1, e.h.ea[n] = !0
                    }

                    t("if"), t("ifnot", !1, !0), t("with", !0)
                }(), e.c.let = {
                    init: function (t, n, i, o, u) {
                        return n = u.extend(n), e.Oa(n, t), {controlsDescendantBindings: !0}
                    }
                }, e.h.ea.let = !0;
                var ue = {};
                e.c.options = {
                    init: function (t) {
                        if (e.a.R(t) !== "select") throw Error("options binding applies only to SELECT elements");
                        for (; 0 < t.length;) t.remove(0);
                        return {controlsDescendantBindings: !0}
                    }, update: function (t, n, i) {
                        function o() {
                            return e.a.jb(t.options, function (p) {
                                return p.selected
                            })
                        }

                        function u(p, w, x) {
                            var g = typeof w;
                            return g == "function" ? w(p) : g == "string" ? p[w] : x
                        }

                        function c(p, w) {
                            if (r && a) e.i.ma(t, e.i.H); else if (y.length) {
                                var x = 0 <= e.a.A(y, e.w.M(w[0]));
                                e.a.Zc(w[0], x), r && !x && e.u.G(e.a.Fb, null, [t, "change"])
                            }
                        }

                        var l = t.multiple, f = t.length != 0 && l ? t.scrollTop : null, s = e.a.f(n()),
                            a = i.get("valueAllowUnset") && i.has("value"), h = i.get("optionsIncludeDestroyed");
                        n = {};
                        var d, y = [];
                        a || (l ? y = e.a.Mb(o(), e.w.M) : 0 <= t.selectedIndex && y.push(e.w.M(t.options[t.selectedIndex]))), s && (typeof s.length > "u" && (s = [s]), d = e.a.jb(s, function (p) {
                            return h || p === m || p === null || !e.a.f(p._destroy)
                        }), i.has("optionsCaption") && (s = e.a.f(i.get("optionsCaption")), s !== null && s !== m && d.unshift(ue)));
                        var r = !1;
                        if (n.beforeRemove = function (p) {
                            t.removeChild(p)
                        }, s = c, i.has("optionsAfterRender") && typeof i.get("optionsAfterRender") == "function" && (s = function (p, w) {
                            c(0, w), e.u.G(i.get("optionsAfterRender"), null, [w[0], p !== ue ? p : m])
                        }), e.a.ec(t, d, function (p, w, x) {
                            return x.length && (y = !a && x[0].selected ? [e.w.M(x[0])] : [], r = !0), w = t.ownerDocument.createElement("option"), p === ue ? (e.a.Bb(w, i.get("optionsCaption")), e.w.cb(w, m)) : (x = u(p, i.get("optionsValue"), p), e.w.cb(w, e.a.f(x)), p = u(p, i.get("optionsText"), x), e.a.Bb(w, p)), [w]
                        }, n, s), !a) {
                            var v;
                            l ? v = y.length && o().length < y.length : v = y.length && 0 <= t.selectedIndex ? e.w.M(t.options[t.selectedIndex]) !== y[0] : y.length || 0 <= t.selectedIndex, v && e.u.G(e.a.Fb, null, [t, "change"])
                        }
                        (a || e.S.Ya()) && e.i.ma(t, e.i.H), e.a.wd(t), f && 20 < Math.abs(f - t.scrollTop) && (t.scrollTop = f)
                    }
                }, e.c.options.$b = e.a.g.Z(), e.c.selectedOptions = {
                    init: function (t, n, i) {
                        function o() {
                            var l = n(), f = [];
                            e.a.D(t.getElementsByTagName("option"), function (s) {
                                s.selected && f.push(e.w.M(s))
                            }), e.m.eb(l, i, "selectedOptions", f)
                        }

                        function u() {
                            var l = e.a.f(n()), f = t.scrollTop;
                            l && typeof l.length == "number" && e.a.D(t.getElementsByTagName("option"), function (s) {
                                var a = 0 <= e.a.A(l, e.w.M(s));
                                s.selected != a && e.a.Zc(s, a)
                            }), t.scrollTop = f
                        }

                        if (e.a.R(t) != "select") throw Error("selectedOptions binding applies only to SELECT elements");
                        var c;
                        e.i.subscribe(t, e.i.H, function () {
                            c ? o() : (e.a.B(t, "change", o), c = e.o(u, null, {l: t}))
                        }, null, {notifyImmediately: !0})
                    }, update: function () {
                    }
                }, e.m.wa.selectedOptions = !0, e.c.style = {
                    update: function (t, n) {
                        var i = e.a.f(n() || {});
                        e.a.P(i, function (o, u) {
                            if (u = e.a.f(u), (u === null || u === m || u === !1) && (u = ""), B) B(t).css(o, u); else if (/^--/.test(o)) t.style.setProperty(o, u); else {
                                o = o.replace(/-(\w)/g, function (l, f) {
                                    return f.toUpperCase()
                                });
                                var c = t.style[o];
                                t.style[o] = u, u === c || t.style[o] != c || isNaN(u) || (t.style[o] = u + "px")
                            }
                        })
                    }
                }, e.c.submit = {
                    init: function (t, n, i, o, u) {
                        if (typeof n() != "function") throw Error("The value for a submit binding must be a function");
                        e.a.B(t, "submit", function (c) {
                            var l, f = n();
                            try {
                                l = f.call(u.$data, t)
                            } finally {
                                l !== !0 && (c.preventDefault ? c.preventDefault() : c.returnValue = !1)
                            }
                        })
                    }
                }, e.c.text = {
                    init: function () {
                        return {controlsDescendantBindings: !0}
                    }, update: function (t, n) {
                        e.a.Bb(t, n())
                    }
                }, e.h.ea.text = !0, function () {
                    if (C && C.navigator) {
                        var t = function (d) {
                            if (d) return parseFloat(d[1])
                        }, n = C.navigator.userAgent, i, o, u, c, l;
                        (i = C.opera && C.opera.version && parseInt(C.opera.version())) || (l = t(n.match(/Edge\/([^ ]+)$/))) || t(n.match(/Chrome\/([^ ]+)/)) || (o = t(n.match(/Version\/([^ ]+) Safari/))) || (u = t(n.match(/Firefox\/([^ ]+)/))) || (c = e.a.W || t(n.match(/MSIE ([^ ]+)/))) || (c = t(n.match(/rv:([^ )]+)/)))
                    }
                    if (8 <= c && 10 > c) var f = e.a.g.Z(), s = e.a.g.Z(), a = function (d) {
                        var y = this.activeElement;
                        (y = y && e.a.g.get(y, s)) && y(d)
                    }, h = function (d, y) {
                        var r = d.ownerDocument;
                        e.a.g.get(r, f) || (e.a.g.set(r, f, !0), e.a.B(r, "selectionchange", a)), e.a.g.set(d, s, y)
                    };
                    e.c.textInput = {
                        init: function (d, y, r) {
                            function v(N, I) {
                                e.a.B(d, N, I)
                            }

                            function p() {
                                var N = e.a.f(y());
                                (N === null || N === m) && (N = ""), S !== m && N === S ? e.a.setTimeout(p, 4) : d.value !== N && (T = !0, d.value = N, T = !1, g = d.value)
                            }

                            function w() {
                                E || (S = d.value, E = e.a.setTimeout(x, 4))
                            }

                            function x() {
                                clearTimeout(E), S = E = m;
                                var N = d.value;
                                g !== N && (g = N, e.m.eb(y(), r, "textInput", N))
                            }

                            var g = d.value, E, S, O = e.a.W == 9 ? w : x, T = !1;
                            c && v("keypress", x), 11 > c && v("propertychange", function (N) {
                                T || N.propertyName !== "value" || O()
                            }), c == 8 && (v("keyup", x), v("keydown", x)), h && (h(d, O), v("dragend", w)), (!c || 9 <= c) && v("input", O), 5 > o && e.a.R(d) === "textarea" ? (v("keydown", w), v("paste", w), v("cut", w)) : 11 > i ? v("keydown", w) : 4 > u ? (v("DOMAutoComplete", x), v("dragdrop", x), v("drop", x)) : l && d.type === "number" && v("keydown", w), v("change", x), v("blur", x), e.o(p, null, {l: d})
                        }
                    }, e.m.wa.textInput = !0, e.c.textinput = {
                        preprocess: function (d, y, r) {
                            r("textInput", d)
                        }
                    }
                }(), e.c.uniqueName = {
                    init: function (t, n) {
                        if (n()) {
                            var i = "ko_unique_" + ++e.c.uniqueName.rd;
                            e.a.Yc(t, i)
                        }
                    }
                }, e.c.uniqueName.rd = 0, e.c.using = {
                    init: function (t, n, i, o, u) {
                        var c;
                        return i.has("as") && (c = {
                            as: i.get("as"),
                            noChildContext: i.get("noChildContext")
                        }), n = u.createChildContext(n, c), e.Oa(n, t), {controlsDescendantBindings: !0}
                    }
                }, e.h.ea.using = !0, e.c.value = {
                    init: function (t, n, i) {
                        var o = e.a.R(t), u = o == "input";
                        if (!u || t.type != "checkbox" && t.type != "radio") {
                            var c = [], l = i.get("valueUpdate"), f = !1, s = null;
                            l && (typeof l == "string" ? c = [l] : c = e.a.wc(l), e.a.Pa(c, "change"));
                            var a = function () {
                                s = null, f = !1;
                                var y = n(), r = e.w.M(t);
                                e.m.eb(y, i, "value", r)
                            };
                            !e.a.W || !u || t.type != "text" || t.autocomplete == "off" || t.form && t.form.autocomplete == "off" || e.a.A(c, "propertychange") != -1 || (e.a.B(t, "propertychange", function () {
                                f = !0
                            }), e.a.B(t, "focus", function () {
                                f = !1
                            }), e.a.B(t, "blur", function () {
                                f && a()
                            })), e.a.D(c, function (y) {
                                var r = a;
                                e.a.Ud(y, "after") && (r = function () {
                                    s = e.w.M(t), e.a.setTimeout(a, 0)
                                }, y = y.substring(5)), e.a.B(t, y, r)
                            });
                            var h;
                            if (h = u && t.type == "file" ? function () {
                                var y = e.a.f(n());
                                y === null || y === m || y === "" ? t.value = "" : e.u.G(a)
                            } : function () {
                                var y = e.a.f(n()), r = e.w.M(t);
                                s !== null && y === s ? e.a.setTimeout(h, 0) : (y !== r || r === m) && (o === "select" ? (r = i.get("valueAllowUnset"), e.w.cb(t, y, r), r || y === e.w.M(t) || e.u.G(a)) : e.w.cb(t, y))
                            }, o === "select") {
                                var d;
                                e.i.subscribe(t, e.i.H, function () {
                                    d ? i.get("valueAllowUnset") ? h() : a() : (e.a.B(t, "change", a), d = e.o(h, null, {l: t}))
                                }, null, {notifyImmediately: !0})
                            } else e.a.B(t, "change", a), e.o(h, null, {l: t})
                        } else e.ib(t, {checkedValue: n})
                    }, update: function () {
                    }
                }, e.m.wa.value = !0, e.c.visible = {
                    update: function (t, n) {
                        var i = e.a.f(n()), o = t.style.display != "none";
                        i && !o ? t.style.display = "" : !i && o && (t.style.display = "none")
                    }
                }, e.c.hidden = {
                    update: function (t, n) {
                        e.c.visible.update(t, function () {
                            return !e.a.f(n())
                        })
                    }
                }, function (t) {
                    e.c[t] = {
                        init: function (n, i, o, u, c) {
                            return e.c.event.init.call(this, n, function () {
                                var l = {};
                                return l[t] = i(), l
                            }, o, u, c)
                        }
                    }
                }("click"), e.ca = function () {
                }, e.ca.prototype.renderTemplateSource = function () {
                    throw Error("Override renderTemplateSource")
                }, e.ca.prototype.createJavaScriptEvaluatorBlock = function () {
                    throw Error("Override createJavaScriptEvaluatorBlock")
                }, e.ca.prototype.makeTemplateSource = function (t, n) {
                    if (typeof t == "string") {
                        n = n || D;
                        var i = n.getElementById(t);
                        if (!i) throw Error("Cannot find template with ID " + t);
                        return new e.C.F(i)
                    }
                    if (t.nodeType == 1 || t.nodeType == 8) return new e.C.ia(t);
                    throw Error("Unknown template type: " + t)
                }, e.ca.prototype.renderTemplate = function (t, n, i, o) {
                    return t = this.makeTemplateSource(t, o), this.renderTemplateSource(t, n, i, o)
                }, e.ca.prototype.isTemplateRewritten = function (t, n) {
                    return this.allowTemplateRewriting === !1 ? !0 : this.makeTemplateSource(t, n).data("isRewritten")
                }, e.ca.prototype.rewriteTemplate = function (t, n, i) {
                    t = this.makeTemplateSource(t, i), n = n(t.text()), t.text(n), t.data("isRewritten", !0)
                }, e.b("templateEngine", e.ca), e.kc = function () {
                    function t(o, u, c, l) {
                        o = e.m.ac(o);
                        for (var f = e.m.Ra, s = 0; s < o.length; s++) {
                            var a = o[s].key;
                            if (Object.prototype.hasOwnProperty.call(f, a)) {
                                var h = f[a];
                                if (typeof h == "function") {
                                    if (a = h(o[s].value)) throw Error(a)
                                } else if (!h) throw Error("This template engine does not support the '" + a + "' binding within its templates")
                            }
                        }
                        return c = "ko.__tr_ambtns(function($context,$element){return(function(){return{ " + e.m.vb(o, {valueAccessors: !0}) + " } })()},'" + c.toLowerCase() + "')", l.createJavaScriptEvaluatorBlock(c) + u
                    }

                    var n = /(<([a-z]+\d*)(?:\s+(?!data-bind\s*=\s*)[a-z0-9\-]+(?:=(?:\"[^\"]*\"|\'[^\']*\'|[^>]*))?)*\s+)data-bind\s*=\s*(["'])([\s\S]*?)\3/gi,
                        i = /\x3c!--\s*ko\b\s*([\s\S]*?)\s*--\x3e/g;
                    return {
                        xd: function (o, u, c) {
                            u.isTemplateRewritten(o, c) || u.rewriteTemplate(o, function (l) {
                                return e.kc.Ld(l, u)
                            }, c)
                        }, Ld: function (o, u) {
                            return o.replace(n, function (c, l, f, s, a) {
                                return t(a, l, f, u)
                            }).replace(i, function (c, l) {
                                return t(l, "<!-- ko -->", "#comment", u)
                            })
                        }, md: function (o, u) {
                            return e.aa.Xb(function (c, l) {
                                var f = c.nextSibling;
                                f && f.nodeName.toLowerCase() === u && e.ib(f, o, l)
                            })
                        }
                    }
                }(), e.b("__tr_ambtns", e.kc.md), function () {
                    e.C = {}, e.C.F = function (i) {
                        if (this.F = i) {
                            var o = e.a.R(i);
                            this.ab = o === "script" ? 1 : o === "textarea" ? 2 : o == "template" && i.content && i.content.nodeType === 11 ? 3 : 4
                        }
                    }, e.C.F.prototype.text = function () {
                        var i = this.ab === 1 ? "text" : this.ab === 2 ? "value" : "innerHTML";
                        if (arguments.length == 0) return this.F[i];
                        var o = arguments[0];
                        i === "innerHTML" ? e.a.fc(this.F, o) : this.F[i] = o
                    };
                    var t = e.a.g.Z() + "_";
                    e.C.F.prototype.data = function (i) {
                        if (arguments.length === 1) return e.a.g.get(this.F, t + i);
                        e.a.g.set(this.F, t + i, arguments[1])
                    };
                    var n = e.a.g.Z();
                    e.C.F.prototype.nodes = function () {
                        var i = this.F;
                        if (arguments.length == 0) {
                            var o = e.a.g.get(i, n) || {},
                                u = o.lb || (this.ab === 3 ? i.content : this.ab === 4 ? i : m);
                            if (!u || o.jd) {
                                var c = this.text();
                                c && c !== o.bb && (u = e.a.Md(c, i.ownerDocument), e.a.g.set(i, n, {
                                    lb: u,
                                    bb: c,
                                    jd: !0
                                }))
                            }
                            return u
                        }
                        o = arguments[0], this.ab !== m && this.text(""), e.a.g.set(i, n, {lb: o})
                    }, e.C.ia = function (i) {
                        this.F = i
                    }, e.C.ia.prototype = new e.C.F, e.C.ia.prototype.constructor = e.C.ia, e.C.ia.prototype.text = function () {
                        if (arguments.length == 0) {
                            var i = e.a.g.get(this.F, n) || {};
                            return i.bb === m && i.lb && (i.bb = i.lb.innerHTML), i.bb
                        }
                        e.a.g.set(this.F, n, {bb: arguments[0]})
                    }, e.b("templateSources", e.C), e.b("templateSources.domElement", e.C.F), e.b("templateSources.anonymousTemplate", e.C.ia)
                }(), function () {
                    function t(s, a, h) {
                        var d;
                        for (a = e.h.nextSibling(a); s && (d = s) !== a;) s = e.h.nextSibling(d), h(d, s)
                    }

                    function n(s, a) {
                        if (s.length) {
                            var h = s[0], d = s[s.length - 1], y = h.parentNode, r = e.ga.instance,
                                v = r.preprocessNode;
                            if (v) {
                                if (t(h, d, function (p, w) {
                                    var x = p.previousSibling, g = v.call(r, p);
                                    g && (p === h && (h = g[0] || w), p === d && (d = g[g.length - 1] || x))
                                }), s.length = 0, !h) return;
                                h === d ? s.push(h) : (s.push(h, d), e.a.Ua(s, y))
                            }
                            t(h, d, function (p) {
                                p.nodeType !== 1 && p.nodeType !== 8 || e.vc(a, p)
                            }), t(h, d, function (p) {
                                p.nodeType !== 1 && p.nodeType !== 8 || e.aa.cd(p, [a])
                            }), e.a.Ua(s, y)
                        }
                    }

                    function i(s) {
                        return s.nodeType ? s : 0 < s.length ? s[0] : null
                    }

                    function o(s, a, h, d, y) {
                        y = y || {};
                        var r = (s && i(s) || h || {}).ownerDocument, v = y.templateEngine || c;
                        if (e.kc.xd(h, v, r), h = v.renderTemplate(h, d, y, r), typeof h.length != "number" || 0 < h.length && typeof h[0].nodeType != "number") throw Error("Template engine must return an array of DOM nodes");
                        switch (r = !1, a) {
                            case"replaceChildren":
                                e.h.va(s, h), r = !0;
                                break;
                            case"replaceNode":
                                e.a.Xc(s, h), r = !0;
                                break;
                            case"ignoreTargetNode":
                                break;
                            default:
                                throw Error("Unknown renderMode: " + a)
                        }
                        return r && (n(h, d), y.afterRender && e.u.G(y.afterRender, null, [h, d[y.as || "$data"]]), a == "replaceChildren" && e.i.ma(s, e.i.H)), h
                    }

                    function u(s, a, h) {
                        return e.O(s) ? s() : typeof s == "function" ? s(a, h) : s
                    }

                    var c;
                    e.gc = function (s) {
                        if (s != m && !(s instanceof e.ca)) throw Error("templateEngine must inherit from ko.templateEngine");
                        c = s
                    }, e.dc = function (s, a, h, d, y) {
                        if (h = h || {}, (h.templateEngine || c) == m) throw Error("Set a template engine before calling renderTemplate");
                        if (y = y || "replaceChildren", d) {
                            var r = i(d);
                            return e.$(function () {
                                var p = a && a instanceof e.fa ? a : new e.fa(a, null, null, null, {exportDependencies: !0}),
                                    v = u(s, p.$data, p), p = o(d, y, v, p, h);
                                y == "replaceNode" && (d = p, r = i(d))
                            }, null, {
                                Sa: function () {
                                    return !r || !e.a.Sb(r)
                                }, l: r && y == "replaceNode" ? r.parentNode : r
                            })
                        }
                        return e.aa.Xb(function (v) {
                            e.dc(s, a, h, v, "replaceNode")
                        })
                    }, e.Qd = function (s, a, h, d, y) {
                        function r(S, O) {
                            e.u.G(e.a.ec, null, [d, S, p, h, v, O]), e.i.ma(d, e.i.H)
                        }

                        function v(S, O) {
                            n(O, w), h.afterRender && h.afterRender(O, S), w = null
                        }

                        function p(S, O) {
                            w = y.createChildContext(S, {
                                as: x, noChildContext: h.noChildContext, extend: function (N) {
                                    N.$index = O, x && (N[x + "Index"] = O)
                                }
                            });
                            var T = u(s, S, w);
                            return o(d, "ignoreTargetNode", T, w, h)
                        }

                        var w, x = h.as,
                            g = h.includeDestroyed === !1 || e.options.foreachHidesDestroyed && !h.includeDestroyed;
                        if (g || h.beforeRemove || !e.Pc(a)) return e.$(function () {
                            var S = e.a.f(a) || [];
                            typeof S.length > "u" && (S = [S]), g && (S = e.a.jb(S, function (O) {
                                return O === m || O === null || !e.a.f(O._destroy)
                            })), r(S)
                        }, null, {l: d});
                        r(a.v());
                        var E = a.subscribe(function (S) {
                            r(a(), S)
                        }, null, "arrayChange");
                        return E.l(d), E
                    };
                    var l = e.a.g.Z(), f = e.a.g.Z();
                    e.c.template = {
                        init: function (s, a) {
                            var h = e.a.f(a());
                            if (typeof h == "string" || "name" in h) e.h.Ea(s); else if ("nodes" in h) {
                                if (h = h.nodes || [], e.O(h)) throw Error('The "nodes" option must be a plain, non-observable array.');
                                var d = h[0] && h[0].parentNode;
                                d && e.a.g.get(d, f) || (d = e.a.Yb(h), e.a.g.set(d, f, !0)), new e.C.ia(s).nodes(d)
                            } else if (h = e.h.childNodes(s), 0 < h.length) d = e.a.Yb(h), new e.C.ia(s).nodes(d); else throw Error("Anonymous template defined, but no template content was provided");
                            return {controlsDescendantBindings: !0}
                        }, update: function (s, a, h, d, y) {
                            var r = a();
                            a = e.a.f(r), h = !0, d = null, typeof a == "string" ? a = {} : (r = "name" in a ? a.name : s, "if" in a && (h = e.a.f(a.if)), h && "ifnot" in a && (h = !e.a.f(a.ifnot)), h && !r && (h = !1)), "foreach" in a ? d = e.Qd(r, h && a.foreach || [], a, s, y) : h ? (h = y, "data" in a && (h = y.createChildContext(a.data, {
                                as: a.as,
                                noChildContext: a.noChildContext,
                                exportDependencies: !0
                            })), d = e.dc(r, h, a, s)) : e.h.Ea(s), y = d, (a = e.a.g.get(s, l)) && typeof a.s == "function" && a.s(), e.a.g.set(s, l, !y || y.ja && !y.ja() ? m : y)
                        }
                    }, e.m.Ra.template = function (s) {
                        return s = e.m.ac(s), s.length == 1 && s[0].unknown || e.m.Id(s, "name") ? null : "This template engine does not support anonymous templates nested within its templates"
                    }, e.h.ea.template = !0
                }(), e.b("setTemplateEngine", e.gc), e.b("renderTemplate", e.dc), e.a.Kc = function (t, n, i) {
                    if (t.length && n.length) {
                        var o, u, c, l, f;
                        for (o = u = 0; (!i || o < i) && (l = t[u]); ++u) {
                            for (c = 0; f = n[c]; ++c) if (l.value === f.value) {
                                l.moved = f.index, f.moved = l.index, n.splice(c, 1), o = c = 0;
                                break
                            }
                            o += c
                        }
                    }
                }, e.a.Pb = function () {
                    function t(n, i, o, u, c) {
                        var l = Math.min, f = Math.max, s = [], a, h = n.length, d, y = i.length, r = y - h || 1,
                            v = h + y + 1, p, w, x;
                        for (a = 0; a <= h; a++) for (w = p, s.push(p = []), x = l(y, a + r), d = f(0, a - 1); d <= x; d++) p[d] = d ? a ? n[a - 1] === i[d - 1] ? w[d - 1] : l(w[d] || v, p[d - 1] || v) + 1 : d + 1 : a + 1;
                        for (l = [], f = [], r = [], a = h, d = y; a || d;) y = s[a][d] - 1, d && y === s[a][d - 1] ? f.push(l[l.length] = {
                            status: o,
                            value: i[--d],
                            index: d
                        }) : a && y === s[a - 1][d] ? r.push(l[l.length] = {
                            status: u,
                            value: n[--a],
                            index: a
                        }) : (--d, --a, c.sparse || l.push({status: "retained", value: i[d]}));
                        return e.a.Kc(r, f, !c.dontLimitMoves && 10 * h), l.reverse()
                    }

                    return function (n, i, o) {
                        return o = typeof o == "boolean" ? {dontLimitMoves: o} : o || {}, n = n || [], i = i || [], n.length < i.length ? t(n, i, "added", "deleted", o) : t(i, n, "deleted", "added", o)
                    }
                }(), e.b("utils.compareArrays", e.a.Pb), function () {
                    function t(o, u, c, l, f) {
                        var s = [], a = e.$(function () {
                            var h = u(c, f, e.a.Ua(s, o)) || [];
                            0 < s.length && (e.a.Xc(s, h), l && e.u.G(l, null, [c, h, f])), s.length = 0, e.a.Nb(s, h)
                        }, null, {
                            l: o, Sa: function () {
                                return !e.a.kd(s)
                            }
                        });
                        return {Y: s, $: a.ja() ? a : m}
                    }

                    var n = e.a.g.Z(), i = e.a.g.Z();
                    e.a.ec = function (o, u, c, l, f, s) {
                        function a(L) {
                            T = {Aa: L, pb: e.ta(w++)}, v.push(T), r || O.push(T)
                        }

                        function h(L) {
                            T = y[L], w !== T.pb.v() && S.push(T), T.pb(w++), e.a.Ua(T.Y, o), v.push(T)
                        }

                        function d(L, K) {
                            if (L) for (var Z = 0, z = K.length; Z < z; Z++) e.a.D(K[Z].Y, function (X) {
                                L(X, Z, K[Z].Aa)
                            })
                        }

                        u = u || [], typeof u.length > "u" && (u = [u]), l = l || {};
                        var y = e.a.g.get(o, n), r = !y, v = [], p = 0, w = 0, x = [], g = [], E = [], S = [], O = [],
                            T, N = 0;
                        if (r) e.a.D(u, a); else {
                            if (!s || y && y._countWaitingForRemove) {
                                var I = e.a.Mb(y, function (L) {
                                    return L.Aa
                                });
                                s = e.a.Pb(I, u, {dontLimitMoves: l.dontLimitMoves, sparse: !0})
                            }
                            for (var I = 0, F, $, H; F = s[I]; I++) switch ($ = F.moved, H = F.index, F.status) {
                                case"deleted":
                                    for (; p < H;) h(p++);
                                    $ === m && (T = y[p], T.$ && (T.$.s(), T.$ = m), e.a.Ua(T.Y, o).length && (l.beforeRemove && (v.push(T), N++, T.Aa === i ? T = null : E.push(T)), T && x.push.apply(x, T.Y))), p++;
                                    break;
                                case"added":
                                    for (; w < H;) h(p++);
                                    $ !== m ? (g.push(v.length), h($)) : a(F.value)
                            }
                            for (; w < u.length;) h(p++);
                            v._countWaitingForRemove = N
                        }
                        e.a.g.set(o, n, v), d(l.beforeMove, S), e.a.D(x, l.beforeRemove ? e.oa : e.removeNode);
                        var j, q, J;
                        try {
                            J = o.ownerDocument.activeElement
                        } catch {
                        }
                        if (g.length) for (; (I = g.shift()) != m;) {
                            for (T = v[I], j = m; I;) if ((q = v[--I].Y) && q.length) {
                                j = q[q.length - 1];
                                break
                            }
                            for (u = 0; p = T.Y[u]; j = p, u++) e.h.Wb(o, p, j)
                        }
                        for (I = 0; T = v[I]; I++) {
                            for (T.Y || e.a.extend(T, t(o, c, T.Aa, f, T.pb)), u = 0; p = T.Y[u]; j = p, u++) e.h.Wb(o, p, j);
                            !T.Ed && f && (f(T.Aa, T.Y, T.pb), T.Ed = !0, j = T.Y[T.Y.length - 1])
                        }
                        for (J && o.ownerDocument.activeElement != J && J.focus(), d(l.beforeRemove, E), I = 0; I < E.length; ++I) E[I].Aa = i;
                        d(l.afterMove, S), d(l.afterAdd, O)
                    }
                }(), e.b("utils.setDomNodeChildrenFromArrayMapping", e.a.ec), e.ba = function () {
                    this.allowTemplateRewriting = !1
                }, e.ba.prototype = new e.ca, e.ba.prototype.constructor = e.ba, e.ba.prototype.renderTemplateSource = function (t, n, i, o) {
                    return (n = !(9 > e.a.W) && t.nodes ? t.nodes() : null) ? e.a.la(n.cloneNode(!0).childNodes) : (t = t.text(), e.a.ua(t, o))
                }, e.ba.Ma = new e.ba, e.gc(e.ba.Ma), e.b("nativeTemplateEngine", e.ba), function () {
                    e.$a = function () {
                        var n = this.Hd = function () {
                            if (!B || !B.tmpl) return 0;
                            try {
                                if (0 <= B.tmpl.tag.tmpl.open.toString().indexOf("__")) return 2
                            } catch {
                            }
                            return 1
                        }();
                        this.renderTemplateSource = function (i, o, u, c) {
                            if (c = c || D, u = u || {}, 2 > n) throw Error("Your version of jQuery.tmpl is too old. Please upgrade to jQuery.tmpl 1.0.0pre or later.");
                            var l = i.data("precompiled");
                            return l || (l = i.text() || "", l = B.template(null, "{{ko_with $item.koBindingContext}}" + l + "{{/ko_with}}"), i.data("precompiled", l)), i = [o.$data], o = B.extend({koBindingContext: o}, u.templateOptions), o = B.tmpl(l, i, o), o.appendTo(c.createElement("div")), B.fragments = {}, o
                        }, this.createJavaScriptEvaluatorBlock = function (i) {
                            return "{{ko_code ((function() { return " + i + " })()) }}"
                        }, this.addTemplate = function (i, o) {
                            D.write("<script type='text/html' id='" + i + "'>" + o + "<\/script>")
                        }, 0 < n && (B.tmpl.tag.ko_code = {open: "__.push($1 || '');"}, B.tmpl.tag.ko_with = {
                            open: "with($1) {",
                            close: "} "
                        })
                    }, e.$a.prototype = new e.ca, e.$a.prototype.constructor = e.$a;
                    var t = new e.$a;
                    0 < t.Hd && e.gc(t), e.b("jqueryTmplTemplateEngine", e.$a)
                }()
            })
        })()
    })()
})(le, le.exports);

var pe = le.exports;
const re = ["Apple", "Banana", "Blueberry", "Boysenberry", "Cherry", "Durian", "Eggplant", "Fig", "Grape", "Guava", "Huckleberry"],
    Te = document.querySelector(".js-combobox"), Oe = new Ce(Te, re);
Oe.init();
const Ne = document.querySelector(".js-select"), Ie = new De(Ne, re);
Ie.init();
const Ae = document.querySelector(".js-multiselect"), Be = new Se(Ae, re);
Be.init();
const ke = document.querySelector(".js-combobox2"), Le = new xe(ke, re);
Le.init();

class Me {
    constructor() {
        this.message = pe.observable("Merhaba, Knockout.js ve Vite!")
    }

    updateMessage() {
        this.message("Güncellendi!")
    }
}

const _e = new Me;
pe.applyBindings(_e);
