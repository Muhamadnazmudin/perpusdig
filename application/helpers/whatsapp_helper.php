<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper WhatsApp Encoder
 * Aman untuk emoji + wa.me / api.whatsapp.com
 */

/**
 * Encode pesan WhatsApp (emoji-safe)
 */
function wa_encode($text)
{
    // pastikan string UTF-8
    $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

    return rawurlencode($text);
}

/**
 * Template pesan WhatsApp Peminjaman
 */
function wa_pesan_peminjaman($p)
{
    $nama   = $p->nama;
    $buku   = '*'.$p->judul.'*';
    $pinjam = date('d-m-Y', strtotime($p->tanggal_pinjam));
    $tempo  = date('d-m-Y', strtotime($p->tanggal_jatuh_tempo));

    switch ($p->status) {

        case 'menunggu':
            $pesan =
                "Assalamualaikum Wr. Wb. 😊\n\n".
                "Yth. Ananda *{$nama}*,\n\n".
                "📚 Kami informasikan bahwa Ananda telah mengajukan peminjaman buku\n".
                "Judul: {$buku}\n\n".
                "Status pengajuan saat ini:\n".
                "⏳ *Menunggu persetujuan petugas perpustakaan*\n\n".
                "Mohon kesediaannya untuk menunggu.\n\n".
                "Terima kasih.\n".
                "📖 Perpustakaan Sekolah";
            break;

        case 'dipinjam':
            $pesan =
                "Assalamualaikum Wr. Wb. 😊\n\n".
                "Yth. Ananda *{$nama}*,\n\n".
                "📖 Saat ini Ananda sedang meminjam buku:\n".
                "{$buku}\n\n".
                "📅 Periode peminjaman:\n".
                "{$pinjam} s.d. {$tempo}\n\n".
                "Mohon agar buku dikembalikan tepat waktu sesuai ketentuan.\n\n".
                "Terima kasih atas kerja sama Ananda.\n".
                "📚 Perpustakaan Sekolah";
            break;

        case 'ditolak':
            $pesan =
                "Assalamualaikum Wr. Wb. 🙏\n\n".
                "Yth. Ananda *{$nama}*,\n\n".
                "📕 Pengajuan peminjaman buku:\n".
                "{$buku}\n\n".
                "Mohon maaf, pengajuan tersebut *belum dapat kami setujui*.\n\n".
                "Silakan menghubungi petugas perpustakaan untuk informasi lebih lanjut.\n\n".
                "Terima kasih.\n".
                "📚 Perpustakaan Sekolah";
            break;

        case 'kembali':
            $pesan =
                "Assalamualaikum Wr. Wb. 😊\n\n".
                "Yth. Ananda *{$nama}*,\n\n".
                "✅ Terima kasih telah mengembalikan buku:\n".
                "{$buku}\n\n".
                "Semoga buku tersebut bermanfaat.\n\n".
                "Hormat kami,\n".
                "📚 Perpustakaan Sekolah";
            break;

        default: // TELAT
            $pesan =
                "Assalamualaikum Wr. Wb. ⚠️\n\n".
                "Yth. Ananda *{$nama}*,\n\n".
                "📕 Peminjaman buku berikut telah melewati batas waktu:\n".
                "{$buku}\n\n".
                "Mohon agar buku segera dikembalikan ke perpustakaan.\n\n".
                "Atas perhatian Ananda, kami ucapkan terima kasih.\n".
                "📚 Perpustakaan Sekolah";
            break;
    }

    return wa_encode($pesan);
}
