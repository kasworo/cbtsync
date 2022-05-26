<?php
if ($level == '1') : ?>
	<nav class="mt-2">
		<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
			<li class="nav-item has-treeview">
				<a href="#" class="nav-link">
					<i class="nav-icon fas fa-database"></i>
					<p>Data Master<i class="fas fa-angle-left right"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<a href="index.php?p=datasekolah" class="nav-link">
							<i class="fas fa-school nav-icon"></i>
							<p>Identitas Sekolah</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="index.php?p=datagtk" class="nav-link">
							<i class="fas fa-user-graduate nav-icon"></i>
							<p>Guru Bidang Studi</p>
						</a>
					</li>

					<li class="nav-item">
						<a href="index.php?p=datakur" class="nav-link">
							<i class="fas fa-graduation-cap nav-icon"></i>
							<p>Kurikulum</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="index.php?p=datamapel" class="nav-link">
							<i class="far fa-check-square nav-icon"></i>
							<p>Mata Pelajaran</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="index.php?p=datasiswa" class="nav-link">
							<i class="fas fa-users nav-icon"></i>
							<p>Biodata Peserta Didik</p>
						</a>
					</li>
				</ul>
			</li>
			<li class="nav-item has-treeview">
				<a href="#" class="nav-link">
					<i class="nav-icon fas fa-book-reader"></i>
					<p>
						Manajemen KBM
						<i class="fas fa-angle-left right"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<a href="index.php?p=datakelas" class="nav-link">
							<i class="fas fa-landmark nav-icon"></i>
							<p>Data Rombel</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="index.php?p=datarombel" class="nav-link">
							<i class="far fa-id-badge nav-icon"></i>
							<p>Anggota Rombel</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="index.php?p=datakkm" class="nav-link">
							<i class="fas fa-award nav-icon"></i>
							<p>Pengaturan KKM</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="index.php?p=dataampu" class="nav-link">
							<i class="fas fa-chalkboard-teacher nav-icon"></i>
							<p>Data Pengampu</p>
						</a>
					</li>
				</ul>
			</li>
			<li class="nav-item has-treeview">
				<a href="#" class="nav-link">
					<i class="nav-icon	fas fa-chart-pie"></i>
					<p>Manajemen Ujian<i class="fas fa-angle-left right"></i></p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<a href="index.php?p=datates" class="nav-link">
							<i class="far fa-calendar-check nav-icon"></i>
							<p>Jenis Tes</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="index.php?p=ruang" class="nav-link">
							<i class="fas fa-chalkboard nav-icon"></i>
							<p>Ruang Ujian</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="index.php?p=jadwal" class="nav-link">
							<i class="far fa-calendar-alt nav-icon"></i>
							<p>Jadwal Ujian</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="index.php?p=datapeserta" class="nav-link">
							<i class="fas fa-id-card nav-icon"></i>
							<p>Peserta Ujian</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="index.php?p=banksoal" class="nav-link">
							<i class="fas fa-chalkboard-teacher nav-icon"></i>
							<p>Bank Soal</p>
						</a>
					</li>
				</ul>
			</li>
			<?php
			$qsetuji = "SELECT*FROM tbujian u INNER JOIN tbjadwal jd USING(idujian) LEFT JOIN  tbsetingujian su USING(idjadwal) WHERE u.status='1'";
			if (cquery($qsetuji) > 0) :
			?>
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="nav-icon far fa-check-square"></i>
						<p>Status Ujian <i class="fas fa-angle-left right"></i></p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="index.php?p=statussoal" class="nav-link">
								<i class="fas fa-list nav-icon"></i>
								<p>Ujikan Bank Soal</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="index.php?p=token" class="nav-link">
								<i class="fas fa-key nav-icon"></i>
								<p>Rilis Token</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="index.php?p=statuspeserta" class="nav-link">
								<i class="fas fa-list-alt nav-icon"></i>
								<p>Status Peserta</p>
							</a>
						</li>
					</ul>
				</li>
			<?php endif ?>
			<li class="nav-item has-treeview">
				<a href="#" class="nav-link">
					<i class="nav-icon fas fa-print"></i>
					<p>Laporan
						<i class="fas fa-angle-left right"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<li class="nav-item">
						<a href="#myLaporTes" class="nav-link btnReport" data-toggle="modal" data-id="1">
							<i class="fas fa-list-alt nav-icon"></i>
							<p>Hasil Tes</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="#myLaporTes" class="nav-link btnReport" data-toggle="modal" data-id="2">
							<i class="fas fa-file-alt nav-icon"></i>
							<p>Rekap Nilai</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="#myLaporTes" class="nav-link btnReport" data-toggle="modal" data-id="3">
							<i class="fas fa-file-pdf nav-icon"></i>
							<p>Rapor Murni</p>
						</a>
					</li>
					<!-- <li class="nav-item">
						<a href="index.php?p=ledger" class="nav-link">
							<i class="far fa-file-pdf nav-icon"></i>
							<p>Rekap Nilai</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="index.php?p=rapor" class="nav-link">
							<i class="far fa-file-pdf nav-icon"></i>
							<p>Rapor Murni</p>
						</a>
					</li> -->
				</ul>
			</li>
			<li class="nav-item">
				<a href="logout.php" class="nav-link">
					<i class="fas fa-power-off nav-icon"></i>
					<p>Keluar</p>
				</a>
			</li>
		</ul>
	</nav>
<?php endif ?>
<?php if ($level == '2') : ?>
	<nav class="mt-2">
		<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
			<li class="nav-item">
				<a href="index.php?p=jadwal" class="nav-link">
					<i class="fas fa-clock nav-icon"></i>
					<p>Jadwal Ujian</p>
				</a>
			</li>
			<li class="nav-item">
				<a href="index.php?p=datapeserta" class="nav-link">
					<i class="fas fa-id-card nav-icon"></i>
					<p>Data Peserta Tes</p>
				</a>
			</li>
			<li class="nav-item">
				<a href="index.php?p=banksoal" class="nav-link">
					<i class="fas fa-archive nav-icon"></i>
					<p>Daftar Bank Soal</p>
				</a>
			</li>
			<?php
			$qsetuji = "SELECT*FROM tbujian u INNER JOIN tbjadwal jd USING(idujian) LEFT JOIN  tbsetingujian su USING(idjadwal) WHERE u.status='1'";
			if (cquery($qsetuji) > 0) :
			?>
				<li class="nav-item">
					<a href="index.php?p=statussoal" class="nav-link">
						<i class="fas fa-list nav-icon"></i>
						<p>Ujikan Bank Soal</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="index.php?p=token" class="nav-link">
						<i class="fas fa-key nav-icon"></i>
						<p>Rilis Token</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="index.php?p=statuspeserta" class="nav-link">
						<i class="fas fa-list-alt nav-icon"></i>
						<p>Lihat Status Peserta</p>
					</a>
				</li>
			<?php endif ?>
			<li class="nav-item has-treeview">
				<a href="#" class="nav-link">
					<i class="nav-icon fas fa-print"></i>
					<p>
						Laporan
						<i class="fas fa-angle-left right"></i>
					</p>
				</a>
				<ul class="nav nav-treeview">
					<?php
					$qwalas = "SELECT*FROM tbrombel INNER JOIN tbgtk g USING(idgtk) INNER JOIN tbuser u USING(username) WHERE u.username='$_COOKIE[id]'";
					$cekwalas = cquery($qwalas);
					if ($cekwalas > 0) :
					?>
						<li class="nav-item">
							<a href="index.php?p=hasiltes" class="nav-link">
								<i class="fas fa-list-alt nav-icon"></i>
								<p>Hasil Tes</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="index.php?p=ledger" class="nav-link">
								<i class="far fa-file-pdf nav-icon"></i>
								<p>Rekap Nilai</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="index.php?p=rapor" class="nav-link">
								<i class="far fa-file-pdf nav-icon"></i>
								<p>Rapor Murni</p>
							</a>
						</li>
					<?php else : ?>
						<li class="nav-item">
							<a href="index.php?p=hasiltes" class="nav-link">
								<i class="fas fa-list-alt nav-icon"></i>
								<p>Hasil Tes</p>
							</a>
						</li>
					<?php endif ?>
				</ul>
			</li>
			<li class="nav-item">
				<a href="logout.php" class="nav-link">
					<i class="fas fa-power-off nav-icon"></i>
					<p>Keluar</p>
				</a>
			</li>
		</ul>
	</nav>
<?php endif ?>