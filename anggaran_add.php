<?php
if (isset($_POST[post_angg])) {
    $jenis = $_POST['jenis']; // Rutin
    $judul = pg_escape_string($_POST['judul']);    // judul project
    $uraian = $_POST['uraian']; // array uraian dari meta data invoice
    $jumlah = $_POST['jumlah']; // array dengan nilai 1 (1 invoice)
    $nominal = $_POST['nominal']; // array total tagihan invoice
    $total = $_POST['total']; // array sum jumlah * nominal
    $grand = floatval(str_replace(',', '', $_POST['grand'])); // sum dari total

    // $_POST['project'] => master data ProjectDana (divisi)
    // $_POST[judul] => master data JudulDana
    // $_POST[acab] => master data CabangDana
    $ck_ats = '0';
    $ck_bid = '0';
    if ($jenis == 'Insidentil' && $grand >= 10000000) {
        $nik_ats = '200304001';
        $nik_keu = '201206001';
    } else if ($cablog != 1) {
        $id_ats = pg_fetch_row(pg_query($conn, "SELECT create_nik FROM dana.kegiatan where id_keg='$judul'"));
        $nik_ats = $id_ats[0];
        $nik_keu = '201212015';
        //$nik_keu mbak LINDA = '200811001';
    } else {
        $id_ats = pg_fetch_row(pg_query($conn, "SELECT nik_pimp FROM dana.token WHERE nik='$niklog'"));
        $nik_ats = $id_ats[0];
        $nik_keu = '201212015';
    }
    $datkeg = pg_fetch_array(pg_query($conn, "SELECT a.*,b.nik_pj FROM dana.kegiatan a, dana.bidang b WHERE a.id_keg='$judul' AND a.id_bid=b.id_bid"));
    if ($jenis == 'Rutin') {
        if ($grand >= 3000000) {
            $ck_ats = '1';
            $ck_bid = '1';
        }
        $project = $_POST['project'];
        $bidang = $datkeg['id_bid'];
    } else {
        $ck_ats = '1';
        $ck_bid = '1';
        $project = $datkeg['id_pro'];
        $bidang = $datkeg['id_bid'];
    }
    $nik_bid = $datkeg['nik_pj'];
    $hp_ats = pg_fetch_array(pg_query($conn, "SELECT hp FROM dana.token WHERE nik='$nik_ats'"));
    $hp_bid = pg_fetch_array(pg_query($conn, "SELECT hp FROM dana.token WHERE nik='$nik_bid'"));
    $hpats = $hp_ats[hp];
    $hpbid = $hp_bid[hp];
    $id_a = pg_fetch_row(pg_query($conn, "SELECT COALESCE(MAX(id_ang), 0) FROM dana.anggaran"));
    $id_a_next = $id_a[0] + 1;

    $insert_anggaran = "INSERT INTO dana.anggaran (id_ang,jenis, id_pro, id_bid, id_keg, status, create_nik, create_date, oto1_status, oto1_nik,oto2_status, oto2_nik, oto3_status, oto3_nik,id_jurnal,ang_cab,cek_pro,cek_bid)
						VALUES ('$id_a_next','$jenis','$project','$bidang','$_POST[judul]','0','$niklog','$timestamp','0','$nik_ats','0','$nik_bid','0','$nik_keu','0','$_POST[acab]','$ck_ats','$ck_bid')";
    $insert_rincian = "INSERT INTO dana.anggaran_detil (id_ang,uraian, jumlah, nominal, total, status) VALUES ";
    // Menggabungkan nilai-nilai yang akan diinsert
    $values = [];
    for ($i = 0; $i < count($uraian); $i++) {
        $values[] = "('$id_a_next','" . pg_escape_string($uraian[$i]) . "', " . floatval(str_replace(',', '', $jumlah[$i])) . ", " . floatval(str_replace(',', '', $nominal[$i])) . ", " . floatval(str_replace(',', '', $total[$i])) . ",'1')";
    }

    // Menggabungkan nilai-nilai menjadi satu string
    $insert_rincian .= implode(", ", $values);

    // Menjalankan query INSERT
    $res_angg = pg_query($conn, $insert_anggaran);
    if ($res_angg) {
        $res_angg_rinc = pg_query($conn, $insert_rincian);
        if ($res_angg_rinc) {
            $pesan = "*PENGAJUAN DANA*\n\nAda pengajuan dana yang membutuhkan validasi silahkan klik https://dana.itnh.systems/index.php?v=anggaran_proses&id_ang=" . $id_a_next;
            if ($hpats == $hpbid) {
                $phones = array($hpats);
            } else {
                $phones = array($hpats, $hpbid); // Isi dengan nomor penerima yang ingin Anda kirim pesan
            }
            foreach ($phones as $receiver) {
                $baseUrl = "https://app.wapakrt.my.id/send-message";
                $params = array(
                    'api_key' => 'AQFhKB2s01TxBsHoT5v3pvBS9X78VeOu',
                    'sender' => '628113421155',
                    'number' => $receiver,
                    'message' => $pesan
                );

                $url = $baseUrl . '?' . http_build_query($params);

                $response = file_get_contents($url);
            }
            echo '<script>window.location.href = "./";</script>';
            exit;
        } else {
            echo "Pengajuan gagal, silahkan ulangi";
        }
    }
    // Membebaskan memori hasil query
    pg_free_result($res_angg);
    pg_free_result($res_angg_rinc);

    // Menutup koneksi ke database
    pg_close($conn);
}
if ($jablog > 0) {
?>
    <div class="oval-div" style="font-size: 12px;">
        <div style="margin: 12px;">
            <form action="" method="post">
                <div class="row form-group">
                    <div class="col-lg-2 col-sm-12">
                        <label class="cslabel">Anggaran</label>
                        <input type="radio" name="jenis" value="Rutin" onclick="tampilkanKegiatan(this)" required> Rutin&emsp;
                        <input type="radio" name="jenis" value="Insidentil" onclick="tampilkanKegiatan(this)" required> Insidentil
                    </div>
                    <div class="col-lg-2 col-sm-12">
                        <label class="cslabel">Cabang</label>
                        <select class="select2 form-control" name="acab" id="acab" style="height: 28px; width:100%;" required>
                            <? $acab = pg_query($conn, "SELECT * FROM dana.cabang WHERE status='1' ORDER BY cabang"); ?>
                            <option value="">-Pilih-</option>
                            <? while ($a = pg_fetch_array($acab)) { ?>
                                <!--option value="<?php echo  $a[id_cabang]; ?>" <?php echo ($a[id_cabang] == $cablog) ? ' selected="selected"' : ''; ?> ><?php echo $a[cabang]; ?></option-->
                                <option value="<?php echo  $a[id_cabang]; ?>"><?php echo $a[cabang]; ?></option>
                            <? } ?>
                        </select>
                    </div>
                    <div id="dpro" class="col-lg-5 col-sm-12" style="display:none">
                        <label class="cslabel">Divisi</label>
                        <select class="select2 form-control" name="project" id="project" style="height: 28px; width:100%;">
                            <? $pro = pg_query($conn, "SELECT * FROM akuntansi.program_pusat WHERE status='1' AND program_pusat_id IN ($prolog) ORDER BY nama"); ?>
                            <option value="">-Pilih-</option>
                            <? while ($a = pg_fetch_array($pro)) { ?>
                                <option value='<? echo $a[program_pusat_id] ?>'><? echo $a[nama] ?></option>
                            <? } ?>
                        </select>
                    </div>
                    <div id="dbid" class="col-lg-5 col-sm-12" style="display:none">
                        <label class="cslabel">Sub Bidang</label>
                        <select class="select2 form-control" name="bidang" id="bidang" style="height: 28px; width:100%;">
                            <? $bid = pg_query($conn, "SELECT * FROM dana.bidang WHERE status='1' ORDER BY bidang"); ?>
                            <option value="">-Pilih-</option>
                            <? while ($a = pg_fetch_array($bid)) { ?>
                                <option value='<? echo $a[id_bid] ?>'><? echo $a[bidang] ?></option>
                            <? } ?>
                        </select>
                    </div>
                    <div class="col-lg-12 col-sm-12">
                        <label class="cslabel">Project</label>
                        <select class="select2 form-control" name="judul" id="judul" style="height: 28px; width:100%;" required>
                            <option value="">-Pilih-</option>
                            <? $query = "SELECT * FROM dana.kegiatan WHERE jenis='$jenis' AND status='1' ORDER BY kegiatan";
                            $result = pg_query($conn, $query);
                            $options = "<option value=''>-Pilih-</option>";
                            while ($row = pg_fetch_assoc($result)) {
                                $options .= "<option value='" . $row['id_keg'] . "'>" . $row['kegiatan'] . "</option>";
                            }
                            ?>
                        </select>
                        <p></p>
                    </div>

                    <div class="col-lg-12  col-sm-12">
                        <table>
                            <thead>
                                <tr>
                                    <th class="wu">Kegiatan</th>
                                    <th class="wj">Jumlah</th>
                                    <th class="wn">Nominal</th>
                                    <th class="wn">Total</th>
                                    <th class="ws">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="input-container">
                                <tr class="input-row">
                                    <td data-label="Kegiatan"><input type="text" name="uraian[]" class="uraian form-control" style="height: 28px; text-align:left; font-size: 14px;" placeholder="Uraian" required></td>
                                    <td data-label="Jumlah"><input type="text" name="jumlah[]" class="form-control" style="height: 28px; text-align:right; font-size: 14px;" oninput="validtext(this)" required></td>
                                    <td data-label="Nominal"><input type="text" name="nominal[]" class="form-control" style="height: 28px; text-align:right; font-size: 14px;" oninput="validtext(this)" required></td>
                                    <td data-label="Total"><input type="text" name="total[]" class="form-control" style="height: 28px; text-align:right; font-size: 14px;" placeholder="Total" value="0" readonly></td>
                                    <td data-label="Status"><input type="text" name="statuse[]" class="form-control" style="height: 28px; font-size: 14px;" value="Planning" readonly></td>
                                    <td data-label="Action"><button type="button" class="btn btn-danger btn-sm hapus-input"><i class="fa fa-trash"></i></button></td>
                                </tr>
                            </tbody>
                            <tr>
                                <td colspan=6 data-label="Total Anggaran">
                                    <input type="text" class="form-control" id="grand-total" name="grand" placeholder="0" style="height: 28px; width:200px; text-align:right; font-size: 16px;" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td colspan=6>
                                    <button type="button" class="btn btn-success btn-sm" id="tambah-input">Tambah</button>
                                    &emsp;<input type="submit" value="Simpan" name="post_angg" class="btn btn-sm btn-primary">
                                    &emsp;<a class="btn btn-sm btn-warning" onclick="window.location.href='./'">Batal</a>
                                </td>
                            </tr>
                        </table>
                    </div>
            </form>
        </div>
    </div>
<? } ?>