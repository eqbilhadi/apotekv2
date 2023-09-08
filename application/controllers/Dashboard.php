<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	public function index()
	{
		check_not_login();
		$today = date('m/d/Y');
		$pembelian = $this->pembelian($today, $today);
		$persediaanAkhir = $this->persediaanAkhir($today, $today);
		$persediaanAwal = $this->persediaanAwal($today, $today);
		$penyesuaian = $this->penyesuaian($today, $today);
		$terjual = $this->terjual($today, $today);
		$databaseApotek = $this->databaseApotek();
		$jmlItemPerKategori = $this->jmlItemPerKategori();
		$dt = $this->jmlPenjualanPembelian($today, $today);
		$inout = $this->inout($today, $today);
		$tgl = array();
		$penjualan = array();
		$laba = array();
		foreach ($dt as $key => $v) {
			$tgl[] = $v->tgl ?? null;
			$penjualan[] = $v->total_penjualan ?? null;
			$laba[] = $v->laba ?? null;
		}
		
		$tglInOut = array();
		$in = array();
		$out = array();
		foreach ($inout as $key => $v) {
			$tglInOut[] = $v->tgl ?? null;
			$in[] = $v->brg_in ?? null;
			$out[] = $v->brg_out ?? null;
		}
		
		$colors = [];
		foreach ($jmlItemPerKategori as $obj) {
			do {
				$color = sprintf('#%02X%02X%02X', rand(0, 255), rand(0, 255), rand(0, 255));
			} while (in_array($color, $colors));
			$obj->warna = $color;
			$colors[] = $color;
		}
		// echo "<pre>";
		// print_r($this->jmlPenjualanPembelian($today, $today));
		// die;
		$data = array(
			'page' => 'Dashboard',
			'pembelian' => $pembelian->total,
			'persediaanAkhir' => $persediaanAkhir,
			'persediaanAwal' => $persediaanAwal->total,
			'penyesuaian' => $penyesuaian->total,
			'terjual' => $terjual->total,
			'database' => $databaseApotek,
			'jmlItemPerKategori' => $jmlItemPerKategori,
			'jmlPenjualanPembelian' => $this->jmlPenjualanPembelian($today, $today),
			'tgl' => json_encode($tgl),
			'penjualan' => json_encode($penjualan),
			'laba' => json_encode($laba),
			'tglInOut' => json_encode($tglInOut),
			'in' => json_encode($in),
			'out' => json_encode($out)
		);
		$this->template->load('template', 'dashboard', $data);
	}
	
	public function riwayatPersediaan()
	{
		$start = $_POST['mulai'];
		$end = $_POST['selesai'];

		$pembelian = $this->pembelian($start, $end);
		$persediaanAkhir = $this->persediaanAkhir($start, $end);
		$persediaanAwal = $this->persediaanAwal($start, $end);
		$penyesuaian = $this->penyesuaian($start, $end);
		$terjual = $this->terjual($start, $end);

		$msg = [
			'pembelian' => $pembelian->total,
			'persediaanAkhir' => $persediaanAkhir,
			'persediaanAwal' => $persediaanAwal->total,
			'penyesuaian' => $penyesuaian->total,
			'terjual' => $terjual->total
		];
		$this->output->set_content_type('application/json')->set_output(json_encode($msg));
	}

	public function laporanTransaksi()
	{
		$start = $_POST['mulai'];
		$end = $_POST['selesai'];
		$dt = $this->jmlPenjualanPembelian($start, $end);
		$dt1 = $this->PenjualanPembelian($start, $end);
		foreach ($dt as $key => $v) {
			$tgl[] = $v->tgl ?? null;
			$penjualan[] = $v->total_penjualan ?? null;
			$laba[] = $v->laba ?? null;
		}
		$msg = [
			'tgl' => $tgl ?? null,
			'penjualan' => $penjualan ?? null,
			'laba' => $laba ?? null,
			'total_penj' => $dt1->total_penjualan ?? null,
			'total_laba' => $dt1->laba ?? null,
		];
		$this->output->set_content_type('application/json')->set_output(json_encode($msg));
	}

	public function laporanInOut()
	{
		$start = $_POST['mulai'];
		$end = $_POST['selesai'];
		$dt = $this->inout($start, $end);
		$dt1 = $this->totalInOut($start, $end);
		foreach ($dt as $key => $v) {
			$tgl[] = $v->tgl ?? null;
			$in[] = $v->brg_in ?? null;
			$out[] = $v->brg_out ?? null;
		}
		$msg = [
			'tgl' => $tgl ?? null,
			'in' => $in ?? null,
			'out' => $out ?? null,
			'total_in' => $dt1->brg_in ?? null,
			'total_out' => $dt1->brg_out ?? null,
		];
		$this->output->set_content_type('application/json')->set_output(json_encode($msg));
	}

	public function tipe()
	{
		$start = $_POST['mulai'];
		$end = $_POST['selesai'];
		$dt = $this->jmlPenjualanPembelian($start, $end);
		foreach ($dt as $key => $v) {
			$tgl[] = $v->tgl ?? null;
			$penjualan[] = $v->total_penjualan ?? null;
			$laba[] = $v->laba ?? null;
		}
		$msg = [
			'tgl' => $tgl ?? null,
			'penjualan' => $penjualan ?? null,
			'laba' => $laba ?? null,
		];
		$this->output->set_content_type('application/json')->set_output(json_encode($msg));
	}

	public function tipeInOut()
	{
		$start = $_POST['mulai'];
		$end = $_POST['selesai'];
		$dt = $this->inout($start, $end);
		foreach ($dt as $key => $v) {
			$tgl[] = $v->tgl ?? null;
			$in[] = $v->brg_in ?? null;
			$out[] = $v->brg_out ?? null;
		}
		$msg = [
			'tgl' => $tgl ?? null,
			'in' => $in ?? null,
			'out' => $out ?? null,
		];
		$this->output->set_content_type('application/json')->set_output(json_encode($msg));
	}

	private function pembelian($start, $end)
	{
		$this->db->select('SUM(td.total_price) AS total');
		$this->db->from('transaksi t');
		$this->db->join('transaksi_d td', 't.`id_transaksi` = td.`id_transaksi`');
		$this->db->where('tgl BETWEEN "' . date('Y-m-d', strtotime($start)) . '" and "' . date('Y-m-d', strtotime($end)) . '"');
		$this->db->where('t.status', 'in');
		$this->db->where('t.type', 'transaksi');
		return $this->db->get()->row();
	}

	private function persediaanAkhir($start, $end)
	{
		$dt = $this->db->query("SELECT (IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) AS persediaan_awal
		FROM
		(
			SELECT
			SUM(CASE WHEN t.`status` = 'in' THEN td.`total_price` END) AS saldo_in,
			SUM(CASE WHEN t.`status` = 'out' THEN td.`qty` * i.`purchase_price` END) AS saldo_out
			FROM transaksi_d td
			JOIN item i ON i.`id_item` = td.`id_item`
			JOIN transaksi t ON t.`id_transaksi` = td.`id_transaksi`
			WHERE tgl < " . "'" . date('Y-m-d', strtotime($start)) . "'" . "
		)f")->row();

		$dt2 = $this->db->query("SELECT (IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) AS total
		FROM
		(
			SELECT
			SUM(CASE WHEN t.`status` = 'in' THEN td.`total_price` END) AS saldo_in,
			SUM(CASE WHEN t.`status` = 'out' THEN td.`qty` * i.`purchase_price` END) AS saldo_out
			FROM transaksi_d td
			JOIN item i ON i.`id_item` = td.`id_item`
			JOIN transaksi t ON t.`id_transaksi` = td.`id_transaksi`
			WHERE tgl BETWEEN " . "'" . date('Y-m-d', strtotime($start)) . "'" . " and " . "'" . date('Y-m-d', strtotime($end)) . "'" . "
		)f")->row();

		$persediaanAkhir = $dt->persediaan_awal + $dt2->total;
		return strval($persediaanAkhir);
	}

	private function persediaanAwal($start, $end)
	{
		return $this->db->query("SELECT (IFNULL(saldo_in,0) - IFNULL(saldo_out,0)) AS total
		FROM
		(
			SELECT
			SUM(CASE WHEN t.`status` = 'in' THEN td.`total_price` END) AS saldo_in,
			SUM(CASE WHEN t.`status` = 'out' THEN td.`qty` * i.`purchase_price` END) AS saldo_out
			FROM transaksi_d td
			JOIN item i ON i.`id_item` = td.`id_item`
			JOIN transaksi t ON t.`id_transaksi` = td.`id_transaksi`
			WHERE tgl < " . "'" . date('Y-m-d', strtotime($start)) . "'" . "
		)f")->row();
	}

	private function penyesuaian($start, $end)
	{
		$this->db->select('SUM(td.total_price) AS total');
		$this->db->from('transaksi t');
		$this->db->join('transaksi_d td', 't.`id_transaksi` = td.`id_transaksi`');
		$this->db->where('tgl BETWEEN "' . date('Y-m-d', strtotime($start)) . '" and "' . date('Y-m-d', strtotime($end)) . '"');
		$this->db->where('t.status', 'in');
		$this->db->where('t.type', 'opname');
		return $this->db->get()->row();
	}

	private function terjual($start, $end)
	{
		$this->db->select('SUM(i.purchase_price * td.qty) AS total');
		$this->db->from('transaksi t');
		$this->db->join('transaksi_d td', 't.`id_transaksi` = td.`id_transaksi`');
		$this->db->join('item i', 'i.id_item = td.id_item');
		$this->db->where('tgl BETWEEN "' . date('Y-m-d', strtotime($start)) . '" and "' . date('Y-m-d', strtotime($end)) . '"');
		$this->db->where('t.status', 'out');
		$this->db->where('t.type', 'transaksi');
		return $this->db->get()->row();
	}

	private function databaseApotek()
	{
		return $this->db->query(
			"	SELECT
					SUM(CASE WHEN table_name = 'supplier' THEN 1 ELSE 0 END) AS supplier,
					SUM(CASE WHEN table_name = 'item' THEN 1 ELSE 0 END) AS item,
					SUM(CASE WHEN table_name = 'customer' THEN 1 ELSE 0 END) AS customer,
					SUM(CASE WHEN table_name = 'location' THEN 1 ELSE 0 END) AS locations,
					SUM(CASE WHEN table_name = 'users' THEN 1 ELSE 0 END) AS user
	  			FROM (
					SELECT 'supplier' AS table_name FROM `supplier`
					UNION ALL
					SELECT 'item' AS table_name FROM `item`
					UNION ALL
					SELECT 'customer' AS table_name FROM `customer`
					UNION ALL
					SELECT 'location' AS table_name FROM `location`
					UNION ALL
					SELECT 'users' AS table_name FROM `user`
	  			) AS table_counts
			"
		)->row();
	}

	private function jmlItemPerKategori()
	{
		return $this->db->query(
			"	SELECT c.name AS kategori, COUNT(i.id_category) AS jml FROM item i
				JOIN category c ON c.`id_category` = i.`id_category`
				GROUP BY c.`id_category`
			"
		)->result();
	}

	private function jmlPenjualanPembelian($start, $end)
	{
		if (isset($_POST['tipe'])) {
			if ($_POST['tipe'] == 'hari') {
				$this->db->select("	CONCAT(DAY(t.tgl), ' ', MONTHNAME(t.tgl)) AS tgl, 
							SUM(CASE WHEN t.status = 'out' THEN td.`total_price` ELSE 0 END) AS total_penjualan,
							SUM(CASE WHEN t.status = 'out' THEN td.qty * (i.selling_price - i.purchase_price) ELSE 0 END) AS laba
						");
				$this->db->group_by('t.tgl');
			} else if ($_POST['tipe'] == 'bulan') {
				$this->db->select("	CONCAT(MONTHNAME(t.tgl), ' ', YEAR(t.tgl)) AS tgl, 
							SUM(CASE WHEN t.status = 'out' THEN td.`total_price` ELSE 0 END) AS total_penjualan,
							SUM(CASE WHEN t.status = 'out' THEN td.qty * (i.selling_price - i.purchase_price) ELSE 0 END) AS laba
						");
				$this->db->group_by('MONTHNAME(t.tgl)');
			} else if ($_POST['tipe'] == 'tahun') {
				$this->db->select("	CONCAT(YEAR(t.tgl)) AS tgl, 
							SUM(CASE WHEN t.status = 'out' THEN td.`total_price` ELSE 0 END) AS total_penjualan,
							SUM(CASE WHEN t.status = 'out' THEN td.qty * (i.selling_price - i.purchase_price) ELSE 0 END) AS laba
						");
				$this->db->group_by('YEAR(t.tgl)');
			}
		} else {
			$this->db->select("	CONCAT(DAY(t.tgl), ' ', MONTHNAME(t.tgl)) AS tgl, 
								SUM(CASE WHEN t.status = 'out' THEN td.`total_price` ELSE 0 END) AS total_penjualan,
								SUM(CASE WHEN t.status = 'out' THEN td.qty * (i.selling_price - i.purchase_price) ELSE 0 END) AS laba
							");
			$this->db->group_by('tgl');
		}
		$this->db->from('transaksi t');
		$this->db->join('transaksi_d td', 't.`id_transaksi` = td.`id_transaksi`');
		$this->db->join('item i', 'i.id_item = td.id_item');
		if ($start != null) {
			$this->db->where('tgl BETWEEN "' . date('Y-m-d', strtotime($start)) . '" and "' . date('Y-m-d', strtotime($end)) . '"');
		}
		$this->db->where('t.type', 'transaksi');
		$this->db->having("total_penjualan != 0 OR laba != 0");
		return $this->db->get()->result();
	}

	private function PenjualanPembelian($start, $end)
	{
		$this->db->select("	
							SUM(CASE WHEN t.status = 'out' THEN td.`total_price` ELSE 0 END) AS total_penjualan,
							SUM(CASE WHEN t.status = 'out' THEN td.qty * (i.selling_price - i.purchase_price) ELSE 0 END) AS laba
						");
		$this->db->from('transaksi t');
		$this->db->join('transaksi_d td', 't.`id_transaksi` = td.`id_transaksi`');
		$this->db->join('item i', 'i.id_item = td.id_item');
		if ($start != null) {
			$this->db->where('tgl BETWEEN "' . date('Y-m-d', strtotime($start)) . '" and "' . date('Y-m-d', strtotime($end)) . '"');
		}
		$this->db->where('t.type', 'transaksi');
		return $this->db->get()->row();
	}

	private function inout($start, $end){
		if (isset($_POST['tipe'])) {
			if ($_POST['tipe'] == 'hari') {
				$this->db->select("	CONCAT(DAY(t.tgl), ' ', MONTHNAME(t.tgl)) AS tgl,
									SUM(CASE WHEN t.status = 'in' THEN td.`qty` ELSE 0 END) AS brg_in,
									SUM(CASE WHEN t.status = 'out' THEN td.`qty` ELSE 0 END) AS brg_out
								");
				$this->db->group_by('t.tgl');
			} else if ($_POST['tipe'] == 'bulan') {
				$this->db->select("	CONCAT(MONTHNAME(t.tgl), ' ', YEAR(t.tgl)) AS tgl, 
									SUM(CASE WHEN t.status = 'in' THEN td.`qty` ELSE 0 END) AS brg_in,
									SUM(CASE WHEN t.status = 'out' THEN td.`qty` ELSE 0 END) AS brg_out
								");
				$this->db->group_by('MONTHNAME(t.tgl)');
			} else if ($_POST['tipe'] == 'tahun') {
				$this->db->select("	CONCAT(YEAR(t.tgl)) AS tgl, 
									SUM(CASE WHEN t.status = 'in' THEN td.`qty` ELSE 0 END) AS brg_in,
									SUM(CASE WHEN t.status = 'out' THEN td.`qty` ELSE 0 END) AS brg_out
								");
				$this->db->group_by('YEAR(t.tgl)');
			}
		} else {
			$this->db->select("	
								CONCAT(DAY(t.tgl), ' ', MONTHNAME(t.tgl)) AS tgl, 
								SUM(CASE WHEN t.status = 'in' THEN td.`qty` ELSE 0 END) AS brg_in,
								SUM(CASE WHEN t.status = 'out' THEN td.`qty` ELSE 0 END) AS brg_out
							");
			$this->db->group_by('tgl');
		}
		$this->db->from('transaksi t');
		$this->db->join('transaksi_d td', 't.`id_transaksi` = td.`id_transaksi`');
		$this->db->join('item i', 'i.id_item = td.id_item');
		if ($start != null) {
			$this->db->where('tgl BETWEEN "' . date('Y-m-d', strtotime($start)) . '" and "' . date('Y-m-d', strtotime($end)) . '"');
		}
		$this->db->where('t.type', 'transaksi');
		return $this->db->get()->result();
	}

	private function totalInOut($start, $end)
	{
		$this->db->select("	
							SUM(CASE WHEN t.status = 'in' THEN td.qty ELSE 0 END) AS brg_in,
							SUM(CASE WHEN t.status = 'out' THEN td.qty ELSE 0 END) AS brg_out
						");
		$this->db->from('transaksi t');
		$this->db->join('transaksi_d td', 't.`id_transaksi` = td.`id_transaksi`');
		$this->db->join('item i', 'i.id_item = td.id_item');
		if ($start != null) {
			$this->db->where('tgl BETWEEN "' . date('Y-m-d', strtotime($start)) . '" and "' . date('Y-m-d', strtotime($end)) . '"');
		}
		$this->db->where('t.type', 'transaksi');
		return $this->db->get()->row();
	}
}
