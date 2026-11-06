var Tbl = (function() {
    /**
     *  TUGAS
     *  - pisahkan export dari table.js
     *
     */
    let a = {
            // table
            aa: null,
            // success crud, simpan | ubah | hapus
            ab: null,
            // center text table
            ac: null,
            // limit load
            ad: null,
            // load pertama
            ae: null,
            // load ke
            af: null,
            // jumlah data keseluruhan
            ag: null,
            // inner loading button
            ah: null,
            // semua button
            ai: null,
            // modal aktif
            aj: null,
            // form aktif
            ak: null,
            // filtered data
            al: null,
            // search status
            am: null,
            // jumlah potongan data
            an: 100,
            // wait send import
            ao: 15000,
            // data export
            ap: null,
            // progres loading swal
            aq: null,
            // jumlah progres
            ar: null,
            // increment progres
            as: null,
            // fungsi setelah selesai reload
            at: null,
        },
        // reload table
        // datatable instance element
        b = (aa) => {
            let ab = null;
            if (aa !== null && aa !== undefined) {
                ab = aa;
            } else if (a.aa !== null && a.aa !== undefined) {
                ab = a.aa;
            }
            ab.clear().draw();
            c.ca(ab);
            a.af = 0;
            a.ag = 0;
            a.ae = true;
            e.ea(ab);
            if (typeof a.at === 'function') {
                a.at();
            }
        },
        c = {
            // indikator load pada table
            // table element
            ca: (aa) => {
                if (aa) {
                    if (Main.adakah($(`td.dataTables_empty`)[0])) {
                        let ab = aa.table().node().querySelector('td.dataTables_empty');
                        ab.innerHTML = 'Memuat..';
                    }
                } else {
                    if (Main.adakah($('td.dataTables_empty')[0])) {
                        let ab = $(`td.dataTables_empty`)[0];
                        ab.innerHTML = 'Memuat..';
                    }
                }
            },
            // indikator tidak ada data
            // table element
            cb: (aa) => {
                if (aa) {
                    if (Main.adakah($(`td.dataTables_empty`)[0])) {
                        let ab = aa.table().node().querySelector('td.dataTables_empty');
                        ab.innerHTML = 'Belum ada data';
                    }
                } else {
                    if (Main.adakah($('td.dataTables_empty')[0])) {
                        let ab = $(`td.dataTables_empty`)[0];
                        ab.innerHTML = 'Belum ada data';
                    }
                }
            },
            cc: (aa, ab) => {
                $(aa).select2({
                    dropdownParent: $(ab),
                    templateResult: function(data) {
                        if (!data.id) {

                            return data.text;
                        }
                        let customize = $(data.element).attr('isi');
                        if (customize && customize.length > 0) {
                            return $(`${customize}`);
                        }
                        return data.text;
                    },
                    templateSelection: function(data) {
                        return data.text;
                    }
                });
            },
            // strtr in js
            // string, replace : {dicari:pengganti, dicari:pengganti}
            cd: (aa, ab) => {
                let ac = new RegExp(Object.keys(ab).join('|'), 'g');
                return aa.replace(ac, ad => ab[ad]);
            }
        },
        // reloading d saat menampilkan halaman
        d = () => {
            if (Main.adakah('DataTable')) {
                // init datatable
                let aa = $('#tbdt').length ? $('#tbdt') : $('.tbdt'),
                    ab = {
                        responsive: true,
                        ordering: true,
                        info: true,
                        lengthChange: true,
                        autoWidth: false,
                        scrollX: true,
                        deferRender: true,
                        scroller: true,
                        aLengthMenu: [
                            [-1, 5, 10, 20, 40, 80, 200],
                            ["Semua", 5, 10, 20, 40, 80, 200]
                        ],
                        iDisplayLength: 10,
                        // lengthMenu: [2, 5, 10, 15, 20, 30, 50, 80, 100, 200, 500],
                        language: {
                            'paginate': {
                                'previous': '<',
                                'next': '>'
                            },
                        },
                    };
                a.am = false;
                // if(aa.length > 0){
                //  if(aa.attr('exp') !== undefined){
                //      ab['dom'] = 'Bfrtip';
                //      ab['buttons'] = [{
                //          extend: 'excelHtml5',
                //          exportOptions: {
                //              columns: ':not(:last-child)'
                //          }
                //      }];
                //  }
                // }
                if (aa) {
                    a.al = [];
                    if (aa.length > 1) {
                        a.aa = [];
                        aa.each((af, ag) => {
                            if ($(ag).attr('ordering') && $(ag).attr('ordering') == 'false') {
                                ab.ordering = false;
                            }
                            if ($(ag).attr('responsive') && $(ag).attr('responsive') == 'false') {
                                ab.responsive = false;
                            }
                            let ah = $(ag).DataTable(ab);
                            a.aa.push(ah);
                            if ($(ag).attr('req') && $(ag).attr('req') === 'false') {} else {
                                setTimeout(() => {
                                    b(ah);
                                }, 500);
                            }
                            a.al[af] = [];
                            ah.on('search.dt', function(xc) {
                                a.am = true;
                                var ac = ah.rows({ filter: 'applied' }).data();
                                a.al[af] = ac.toArray();
                            });
                        });
                    } else {
                        if (aa.attr('ordering') && aa.attr('ordering') == 'false') {
                            ab.ordering = false;
                        }
                        if (aa.attr('responsive') && aa.attr('responsive') == 'false') {
                            ab.responsive = false;
                        }
                        a.aa = aa.DataTable(ab);
                        aa.on('search.dt', function(xz) {
                            a.al = [];
                            a.am = true;
                            var ac = a.aa.rows({ filter: 'applied' }).data();
                            a.al = ac.toArray();
                        });
                        if (aa.attr('req') && aa.attr('req') === 'false') {} else {
                            setTimeout(() => {
                                b(a.aa);
                            }, 500);
                        }
                    }
                }
            }
            $('#mdTbh').on('show.bs.modal', () => {
                a.ak = document.getElementById('frTbh');
                console.log('a.ak:', a.ak);
                a.ak.addEventListener('submit', f.fd);
                a.aj = $('#mdTbh');
            });
            $('#mdTbh').on('hide.bs.modal', () => {
                if (a.ak !== null) {
                    a.ak.removeEventListener('submit', f.fd);
                    a.ak.reset();
                    if ($('#frTbh').find('.select2').length > 0) {
                        $('#frTbh').find('.select2').each((aa, ab) => {
                            $(ab).val('').change();
                        });
                    }
                    a.ak = null;
                }
                if (a.aj !== null) {
                    a.aj = null;
                }
            });
            if (Main.adakah('#btexp')) {
                $('#btexp').click((ad) => {
                    e.ed($('#btexp')[0]);
                });
            }
            if (Main.adakah('.select2')) {
                $('.select2').each((aa, ab) => {
                    if (!$(ab).data('select2-id')) {
                        c.cc(ab, ab.parentElement);
                    }
                });
            }
        },
        e = {
            // request data
            // table element
            ea: (af) => {
                let aa = true;
                Main.send(null, '', { a: '00', b: dtbl, c: a.af, d: a.ae, e: a.ad }, {
                    b: () => {},
                    s: (aa) => {
                        if (aa.h && aa.h === true) {
                            if (a.ae === true) {
                                a.ae = false;
                                a.ag = aa.t;
                                e.eb(aa.i, af);
                                let ac = Math.ceil(aa.t / a.ad);
                                if (ac > 1) {
                                    aa = false;
                                    for (let ad = 1; ad < ac; ad++) {
                                        let ae = a.ad * ad;
                                        setTimeout(() => {
                                            e.ec(ae, af);
                                        }, 500 * ad);
                                    }
                                }
                            }
                        } else if (aa.h && aa.h == 'p') {
                            window.location.href = aa.i;
                        } else {
                            if (aa.h && aa.h === false) {
                                Main.pesan(aa.i, aa.t);
                            }
                            c.cb(af);
                        }
                    },
                    e: (ab) => {
                        if (ab.status === 404) {
                            Main.pesan('error', 'Token Tidak Sesuai<br>Muat Ulang Halaman', 3300);
                        } else {
                            Main.pesan('error', 'Terjadi Kesalahan', 2000);
                        }
                    },
                    c: () => {}
                });
            },
            // mengisi data kedalam table
            // array data, table element
            eb: (aa, ab) => {
                if (ab != null) {
                    ab.rows.add(aa).draw();
                } else {
                    throw new Error('Tidak ada tabel yang akan di load');
                }
            },
            ec: (aa, ad) => {
                Main.send(null, '', { a: '00', b: dtbl, c: aa, d: false, e: a.ad }, {
                    b: () => {},
                    s: (ab) => {
                        if (ab.h && ab.h === true) {
                            e.eb(ab.i, ad);
                            if (aa + ab.i.length >= a.ag) {
                                r();
                            }
                        } else if (aa.h && aa.h == 'p') {
                            window.location.href = aa.i;
                        }
                    },
                    e: (ac) => {},
                    c: () => {}
                });
            },
            // export
            ed: (aa) => {
                let ab = [];
                if (a.am === true) {
                    for (ad = 0; ad < a.al.length; ad++) {
                        ab.push(a.al[ad][parseInt(aa.getAttribute('ck'))]);
                    }
                } else {
                    let ad = a.aa.rows().data().toArray();
                    for (let ae = 0; ae < ad.length; ae++) {
                        ab.push(ad[ae][parseInt(aa.getAttribute('ck'))]);
                    }
                }
                a.ap = ab;
                ab = null;
                if (a.ap.length > 0) {
                    Main.konfirmasi('Export Data Ke Excel', 'Ingin Export <b>' + a.ap.length + '</b> Data Ke Excel ?',
                        (ae) => {
                            if (ae === true) {
                                // d:null = jumlah potongan data
                                let ac = aa.getAttribute('pl'),
                                    am = aa.getAttribute('tv'),
                                    ad = { a: '07', b: dtbl, c: JSON.stringify(a.ap), d: null },
                                    ae = {
                                        title: "Export Data Ke Excel",
                                        html: `Total Data Diexport <b>${a.ap.length}</b><br>
                                <div class="progress"><div id="dynamic" class="progress-bar
                                progress-bar-success progress-bar-striped
                                progress-bar-animated active"
                                role="progressbar" aria-valuenow="0"
                                aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div>`,
                                        allowOutsideClick: false,
                                        showConfirmButton: false,
                                    },
                                    ak = Math.ceil(a.ap.length / a.an),
                                    al = () => {
                                        a.aq = $(Swal.getPopup()).find('#dynamic');
                                        ad['g'] = (Date.now() % 1000) / 1000;
                                        if (a.aq.length > 0) {
                                            if (ak > 1) {
                                                a.ar = 0;
                                                // jumlah potongan data
                                                ad['d'] = ak;
                                                a.as = 100 / ak;
                                                // data, jumlah potongan
                                                e.ef(ad, ak);
                                            } else {
                                                a.ar = 100;
                                                e.ee(ad);
                                            }
                                        } else {
                                            Swal.close();
                                        }
                                    };
                                if (am !== undefined && am !== null) {
                                    a.ao = parseInt(am);
                                }
                                if (ac !== undefined && ac !== null) {
                                    let af = ac.split('|'),
                                        ag = {};
                                    for (let ah = 0; ah < af.length; ah++) {
                                        let ai = af[ah].split(';');
                                        ag[ai[0]] = ai[1];
                                    }
                                    Swal.fire({
                                        title: "Pilih Yang Akan Diexport",
                                        input: "select",
                                        inputOptions: ag,
                                        inputPlaceholder: "-- Pilih --",
                                        showCancelButton: true,
                                        confirmButtonText: 'Export',
                                        confirmButtonColor: '#25b025',
                                        cancelButtonText: 'Batal',
                                        cancelButtonColor: '#bf0606',
                                        inputValidator: (aj) => {
                                            if (!aj) {
                                                return 'Harus Dipilih !';
                                            }
                                        }
                                    }).then((dt) => {
                                        if (dt.value !== undefined) {
                                            ad['f'] = dt.value;
                                            ae['didOpen'] = al;
                                            Swal.fire(ae);
                                        }
                                    });
                                } else {
                                    ae['didOpen'] = al;
                                    Swal.fire(ae);
                                }
                            }
                        });
                } else {
                    Main.pesan('error', 'Data Belum Ada');
                }
                // Swal.fire({
                //  title: "Export Data Ke Excel",
                //  html: "Total Data Diexport : <b></b>",
                //  // allowOutsideClick:false,
                //  didOpen: () => {
                //      Swal.showLoading();
                //      let ab = Swal.getPopup().querySelector("b"),
                //      ac = [], ad = null;

                //      ab.textContent = `${ac.length}`;
                //      Main.send(null, '', {a:'h', b:dtbl, c:ac.length, d:ac},{
                //          s:(ad)=>{
                //              console.log(ad);
                //          },
                //          e:(ae)=>{}
                //      });
                //      // Swal.close();

                //  }
                // });
            },
            // send chunk data
            // data
            ee: (aa) => {
                Main.send(null, '', aa, {
                    s: (ab) => {
                        if (ab && ab.h === true) {
                            if (ab && ab.d === true) {
                                let ad = Main.buat.a(ab.i, null, null, null, { download: ab.n });
                                document.body.appendChild(ad);
                                ad.click();
                                document.body.removeChild(ad);
                                Swal.close();
                                a.aq = null;
                                a.ar = null;
                                a.as = null;
                            } else {
                                a.ar = a.ar + a.as;
                                let ae = (Math.round(a.ar * 100) / 100).toFixed(2);
                                $(a.aq)
                                    .css("width", ae + "%")
                                    .attr("aria-valuenow", ae)
                                    .text(ae + "% Complete");
                            }
                        }
                    },
                    e: (ac) => { Main.pesan('error', 'Terjadi Kesalahan Saat Export Data') }
                });
            },
            // data, jumlah potongan
            ef: async (aa, ab) => {
                for (let ac = 0; ac < ab; ac++) {
                    let ad = a.ap.splice(0, a.an);
                    // potongan data
                    aa['c'] = JSON.stringify(ad);
                    // potongan ke brapa
                    aa['e'] = ac;
                    e.ee(aa);
                    await e.eg(a.ao);
                }
            },
            eg: (aa) => {
                return new Promise(ab => setTimeout(ab, aa));
            }
        },
        f = {
            // ubah
            fa: (aa) => {
                let ai = aa.innerHTML;
                aa.innerHTML = a.ah;
                g.ga();
                let ab = aa.getAttribute('apa'),
                    ac = aa.getAttribute('ini');
                a.ak = document.getElementById('frUbh');
                a.aj = $('#mdUbh');
                Main.send(null, '', { a: '01', b: dtbl, c: ab, d: ac }, {
                    s: (ad) => {
                        if (ad.h && ad.h === true) {
                            let aj = {};
                            if (ad.f) {
                                a.ak.innerHTML = ad.f;
                                let am = $(a.ak).find('.select2');
                                if (am.length > 0) {
                                    am.each((ai, sf) => {
                                        c.cc(am[0], am[0].parentElement);
                                    });
                                }
                            } else {
                                Object.keys(ad.i).forEach((af) => {
                                    if (af == 'inner') {
                                        Object.keys(ad.i[af]).forEach((ah) => {
                                            let ai = $(a.ak).find('[name="' + ah + '"]');
                                            if (ai.length > 0) {
                                                ai[0].innerHTML = ad.i[af][ah];
                                            }
                                        });
                                    } else if (af == 'select') {
                                        Object.keys(ad.i[af]).forEach((ag) => {
                                            let sf = $(a.ak).find('[name="' + ag + '"]'),
                                                ak = sf[0].getAttribute('pend'),
                                                trig = sf[0].getAttribute('trig');
                                            if (ak !== undefined) {
                                                aj[ag] = sf[0].innerHTML;
                                            }
                                            sf.val(ad.i[af][ag]); //.trigger('change')
                                        });
                                    } else {
                                        $(a.ak).find('[name="' + af + '"]').val(ad.i[af]);
                                    }
                                });
                                Object.keys(aj).forEach((al) => {
                                    let am = $(a.ak).find('[name="' + al + '"]');
                                    if (am.length > 0) {
                                        if (am.hasClass('select2')) {
                                            c.cc(am[0], am[0].parentElement);
                                            if (!am[0].hasAttribute('onchange')) {
                                                am[0].onchange = () => {
                                                    Tbl.cari(am[0]);
                                                }
                                            }
                                        }
                                    }
                                });
                            }
                            a.ak.addEventListener('submit', f.fc);
                            a.aj.on('hidden.bs.modal', () => {
                                if (a.ak !== null) {
                                    Object.keys(aj).forEach((ao) => {
                                        let ap = $(a.ak).find('[name="' + ao + '"]');
                                        if (ap.length > 0) {
                                            if (!ap[0].hasAttribute('onchange')) {
                                                ap[0].onchange = null;
                                            }
                                            if ($(ap[0]).hasClass('select2')) {
                                                $(ap[0]).select2('destroy');
                                            }
                                            ap[0].innerHTML = aj[ao];
                                        }
                                    });
                                    a.ak.removeEventListener('submit', f.fc);
                                    a.ak.reset();
                                    a.ak = null;
                                }
                                if (a.aj !== null) {
                                    a.aj = null;
                                }
                                $(this).unbind();
                            });
                            a.aj.modal('show');
                        } else {
                            if (ad.h && ad.h == 'p') {
                                window.location.href = ad.i;
                            } else {
                                Main.pesan(ad.i, ad.t, 2000);
                            }
                        }
                    },
                    e: (ae) => {
                        if (ae.status === 404) {
                            Main.pesan('error', 'Token Tidak Sesuai<br>Muat Ulang Halaman', 3300);
                        } else {
                            Main.pesan('error', 'Terjadi Kesalahan', 2000);
                        }
                    },
                    c: () => {
                        aa.innerHTML = ai;
                        g.gb();
                    }
                });
            },
            // hapus
            // button
            fb: (aa) => {
                let ab = aa.getAttribute('itu'),
                    ac = aa.getAttribute('apa'),
                    ah = aa.getAttribute('anu'),
                    ai = aa.getAttribute('ane'),
                    aj = aa.getAttribute('ani');
                // title, text, callback, confirm text, cancel text, icon
                Main.konfirmasi('Hapus ' + ah, 'Ingin Menghapus ' + ai + ' " ' + ab +
                    ' " ?<br><b>Aksi Tidak Dapat Dikembalikan</b>', (ad) => {
                        if (ad === true) {
                            let ae = aa.innerHTML;
                            aa.innerHTML = a.ah;
                            g.ga();
                            if (aj !== undefined && aj !== null) {
                                // attr swal, callback, form id, send attr, error attr
                                // attr swal : {title:'',html:'',showCancel:bool,confirmColor:'#',
                                //              cancelColor:'#',
                                //              confirmText:'', cancelText:'', focusConfirm:bool}
                                // form id : 'form id'
                                // send attr : {u:url, c:callback send}
                                Main.formConfirm({
                                    title: 'Alasan Menghapus',
                                    html: `<form id="frCnf" onsubmit="return false">
                                        <div class="form-floating mb-3">
                                            <textarea name="alasan" required class="form-control"></textarea>
                                            <label class="text-dark">Alasan</label>
                                        </div>
                                        <input type="text" hidden name="a" value="04">
                                        <input type="text" hidden name="b" value="${dtbl}">
                                        <input type="text" hidden name="c" value="tbl">
                                        <input type="text" hidden name="d" value="${ac}">
                                    </form>`,
                                }, null, 'frCnf', {
                                    c: {
                                        s: (ak) => {
                                            Main.pesan(ak.i, ak.t);
                                            if (ak.h === true) {
                                                b();
                                            }
                                        }
                                    }
                                }, 'Harus Diisi');
                            } else {
                                Main.send(null, '', { a: '04', b: dtbl, c: 'tbl', d: ac }, {
                                    b: () => {},
                                    s: (af) => {
                                        if (af.h && af.h === true) {
                                            Main.pesan(af.i, af.t);
                                            b();
                                        } else if (af.h && af.h == 'p') {
                                            window.location.href = af.i;
                                        } else {
                                            Main.pesan(af.i, af.t);
                                        }
                                    },
                                    e: (ag) => {
                                        if (ag.status === 404) {
                                            Main.pesan('error', 'Token Tidak Sesuai<br>Muat Ulang Halaman', 3300);
                                        } else {
                                            Main.pesan('error', 'Terjadi Kesalahan', 2000);
                                        }
                                    },
                                    c: () => {}
                                });
                            }
                            g.gb();
                            aa.innerHTML = ae;
                        }
                    }, 'Hapus', 'Batal', 'danger');
            },
            // kirim ubah
            // event
            fc: (aa) => {
                if (a.ak !== null) {
                    aa.preventDefault();
                    let ab = $(a.ak).find('button[type="submit"]')[0],
                        ac = ab.innerHTML;
                    ab.innerHTML = a.ah;
                    g.ga();
                    Main.send(aa, '', a.ak, {
                        s: (ad) => {
                            if (ad.h && ad.h === true) {
                                Main.pesan(ad.i, ad.t);
                                b();
                                a.aj.modal('hide');
                            } else if (ad.h && ad.h == 'p') {
                                window.location.href = ad.i;
                            } else {
                                Main.pesan(ad.i, ad.t);
                            }
                        },
                        e: (ae) => {
                            if (ae.status === 404) {
                                Main.pesan('error', 'Token Tidak Sesuai<br>Muat Ulang Halaman', 3300);
                            } else {
                                Main.pesan('error', 'Terjadi Kesalahan', 2000);
                            }
                        },
                        c: () => {
                            ab.innerHTML = ac;
                            g.gb();
                        }
                    });
                }
            },
            // simpan
            fd: (aa) => {
                let ab = $(a.ak).find('button[type="submit"]')[0],
                    ac = ab.innerHTML;
                ab.innerHTML = a.ah;
                g.ga();
                Main.send(aa, '', a.ak, {
                    b: () => {},
                    s: (ad) => {
                        if (ad) {
                            if (ad.h == 'p') {
                                window.location.href = ad.i;
                            } else {
                                Main.pesan(ad.i, ad.t);
                                if (ad.h === true) {
                                    b();
                                    if (typeof a.ab === 'function')
                                        a.ab('simpan');
                                    a.aj.modal('hide');
                                }
                            }
                        }
                    },
                    e: (ae) => {
                        if (ae.status === 404) {
                            Main.pesan('error', 'Token Tidak Sesuai<br>Muat Ulang Halaman', 3300);
                        } else {
                            Main.pesan('error', 'Terjadi Kesalahan', 2000);
                        }
                    },
                    c: () => {
                        ab.innerHTML = ac;
                        g.gb();
                    }
                });
            },
            // nik change
            fe: (aa, ab) => {
                Main.send(ab, '', { a: '0', b: dtbl, c: aa.value, d: 'nik' }, {
                    s: (ac) => {
                        if (ac.h) {
                            Object.keys(ac.i).forEach((ae) => {
                                if (Main.adakah('#' + ae)) {
                                    $('#' + ae).val(ac.i[ae]);
                                }
                            });
                        } else {
                            Main.pesan(ac.i, ac.p);
                        }
                    },
                    e: (ad) => {}
                });
            },
        },
        // disabled all button
        g = {
            ga: () => {
                let aa = $('button');
                if (aa.length > 0) {
                    aa.each((ab, ac) => {
                        ac.setAttribute('disabled', 'disabled');
                    });
                }
            },
            gb: () => {
                let aa = $('button');
                if (aa.length > 0) {
                    aa.each((ab, ac) => {
                        ac.removeAttribute('disabled');
                    });
                }
            },
            gc: (aa) => {
                let ab = aa.getAttribute('apa');
                if (ab) {
                    let ac = null,
                        ad = null,
                        ag = null,
                        ai = {
                            kab: '<option value="">-- Pilih Provinsi Dahulu --</option>',
                            kec: '<option value="">-- Pilih Kabupaten / Kota Dahulu --</option>',
                            kel: '<option value="">-- Pilih Kecamatan Dahulu --</option>',
                            rt: '<option value="">-- Pilih Kelurahan / Desa Dahulu --</option>',
                            rw: '<option value="">-- Pilih RT Dahulu --</option>',
                            satker: '<option value="">-- Pilih Instansi Dahulu --</option>',
                            unker: '<option value="">-- Pilih Satuan Kerja Dahulu --</option>',
                        };
                    switch (ab) {
                        case 'prov':
                            // nilai dikirim
                            ac = ['prov'];
                            // nilai diubah
                            ad = ['kab', 'kec', 'kel', 'rt', 'rw'];
                            // element di isi
                            ag = 'kab';
                            break;
                        case 'kab':
                            // nilai dikirim
                            ac = ['prov', 'kab'];
                            // nilai diubah
                            ad = ['kec', 'kel', 'rt', 'rw'];
                            // element di isi
                            ag = 'kec';
                            break;
                        case 'kec':
                            // nilai dikirim
                            ac = ['prov', 'kab', 'kec'];
                            // nilai diubah
                            ad = ['kel', 'rt', 'rw'];
                            // element di isi
                            ag = 'kel';
                            break;
                        case 'kel':
                            ac = ['prov', 'kab', 'kec', 'kel'];
                            ad = ['rt', 'rw'];
                            ag = 'rt';
                            break;
                        case 'rt':
                            ac = ['prov', 'kab', 'kec', 'kel', 'rt'];
                            ad = ['rw'];
                            ag = 'rw';
                            break;
                        case 'instansi':
                            ac = ['instansi'];
                            ad = ['satker', 'unker'];
                            ag = 'satker';
                            break;
                        case 'satker':
                            ac = ['instansi', 'satker'];
                            ad = ['unker'];
                            ag = 'unker';
                            break;
                    }
                    let af = $(aa).closest('form');
                    if (af.length > 0) {
                        for (let ae = 0; ae < ad.length; ae++) {
                            let ah = $(af).find('[name="' + ad[ae] + '"]');
                            if (ah.length > 0) {
                                ah[0].innerHTML = ai[ad[ae]];
                                ah.val('').trigger('change');
                            }
                        }
                        g.gd(ac, af, ag);
                    }
                }
            },
            // array, form, target
            gd: (aa, ab, af) => {
                let ac = { a: '06', b: dtbl };
                for (let ad = 0; ad < aa.length; ad++) {
                    let ae = $(ab).find('[name="' + aa[ad] + '"]');
                    if (ae) {
                        ac[aa[ad]] = ae.val();
                    }
                }
                Main.send(null, '', ac, {
                    s: (ag) => {
                        if (ag.h) {
                            if (ag.h == 'p') {
                                window.location.href = ag.i;
                            } else if (ag.h === true) {
                                let ai = $(ab).find('[name="' + af + '"]');
                                if (ai.length > 0) {
                                    ai[0].innerHTML = ag.i;
                                }
                            }
                        }
                    },
                    e: (ah) => {
                        Main.pesan('error', 'Kesalahan mengambil data');
                    }
                });
            }
        },
        // detail
        h = (aa) => {
            g.ga();
            let ab = aa.getAttribute('apa'),
                ac = aa.getAttribute('ini'),
                af = $('#frDet');
            Main.send(null, '', { a: '05', b: dtbl, c: ab, d: 'tbl' }, {
                s: (ad) => {
                    if (ad && ad.h === true) {
                        if (ad.f) {
                            af[0].innerHTML = ad.f;
                            Object.keys(ad.i).forEach((ah) => {
                                let ag = af.find('[name="' + ah + '"]');
                                if (ag.length > 0) {
                                    if (ah == 'img') {
                                        let ai = atob(ad.i[ah]),
                                            aj = ai.length,
                                            ak = new Uint8Array(aj),
                                            am = 'image/png';
                                        for (let al = 0; al < aj; al++) {
                                            ak[al] = ai.charCodeAt(al);
                                        }
                                        if (ak[0] === 0x89 && ak[1] === 0x50 && ak[2] === 0x4E && ak[3] === 0x47) {
                                            am = 'image/png';
                                        } else if (ak[0] === 0xFF && ak[1] === 0xD8 && ak[ak.length - 2] === 0xFF && ak[ak.length - 1] === 0xD9) {
                                            am = 'image/jpeg';
                                        } else if (ak[0] === 0x47 && ak[1] === 0x49 && ak[2] === 0x46 && ak[3] === 0x38) {
                                            am = 'image/gif';
                                        } else {
                                            am = 'image/jpg';
                                        }
                                        $('#fdet')[0].src = 'data:' + am + ';base64,' + ad.i[ah];
                                    } else {
                                        ag.html(ad.i[ah]);
                                    }
                                }
                            });
                            $('#mdDet').on('hide.bs.modal', () => {
                                if ($('#fdet').length > 0) {
                                    $('#fdet')[0].src = '';
                                }
                                af[0].innerHTML = '';
                            });
                            $('#mdDet').modal('show');
                        }
                    } else if (ad.h && ad.h == 'p') {
                        window.location.href = ad.i;
                    } else {
                        Main.pesan(ad.i, ad.t);
                    }
                },
                e: (ae) => {},
                c: () => {
                    g.gb();
                }
            });
        },
        r = () => {
            a.ad = 500;
            a.ae = true;
            a.af = 0;
            a.ag = 0;
        },
        n = () => {
            if (a.ad === null) {
                // init limit load
                a.ad = 500;
            }
            if (a.ae === null) {
                // init load pertama
                a.ae = true;
            }
            if (a.ag === null) {
                // init jumlah data
                a.ag = 0;
            }
            if (a.af === null) {
                // init load ke
                a.af = 0;
            }
            if (a.an === null) {
                // jumlah potongan data
                a.an = 100;
            }
        },
        s = () => {
            a.ah = '<i class="fad fa-spinner-third fa-spin-pulse text-dark"></i>';
            let aa = $('#frUbh').find('input[name="b"]');
            if (aa[0] !== undefined) {
                aa[0].setAttribute('value', dtbl);
            }
            var ab = $('#frTbh').find('input[name="b"]');
            if (ab[0] !== undefined)
                ab[0].setAttribute('value', dtbl);
        };
    return {
        init: () => {
            s(), n(), d();
        },
        ubah: (aa) => {
            f.fa(aa);
        },
        detail: (aa) => {
            h(aa);
        },
        hapus: (aa) => {
            f.fb(aa);
        },
        ganti: (aa, bb) => {
            f.fe(aa, bb);
        },
        cari: (aa) => {
            g.gc(aa);
        },
        modalAktif: () => {
            return a.aj;
        },
        formAktif: () => {
            return a.ak;
        },
        table: () => {
            return a.aa;
        },
        reload: () => {
            b();
        },
        // datatable instance
        loadTo: (aa) => {
            b(aa);
        },
        selesaiReload: (aa) => {
            if (typeof window[aa] === 'function') {
                a.at = window[aa];
            }
        },
        successCrud(aa) {
            if (typeof aa === 'function') {
                a.ab = aa;
            }
        }
    }
})();
siap(() => {
    Tbl.init();
}, ['DataTable']);