<?php
/* Generate ECI single-byte tables & routines from unicode.org mapping files */
/*
    libzint - the open source barcode library
    Copyright (C) 2022 Robin Stuart <rstuart114@gmail.com>
*/
/* SPDX-License-Identifier: BSD-3-Clause */
/*
 * To create "backend/eci_sb.h" (from project root directory):
 *
 *   php backend/tools/gen_eci_sb_h.php
 *
 * Requires "8859-*.TXT" from https://unicode.org/Public/MAPPINGS/ISO8859/
 * and "CP1250/1/2/6.TXT" from https://unicode.org/Public/MAPPINGS/VENDORS/MICSFT/
 * to be in "backend/tools/data" directory.
 */

$basename = basename(__FILE__);
$dirname = dirname(__FILE__);

$opts = getopt('d:o:');
$data_dirname = isset($opts['d']) ? $opts['d'] : ($dirname . '/data'); // Where to load file from.
$out_dirname = isset($opts['o']) ? $opts['o'] : ($dirname . '/..'); // Where to put output.

$out = array();

$head = <<<'EOD'
/*  eci_sb.h - Extended Channel Interpretations single-byte, generated by "backend/tools/gen_eci_sb_h.php"
    from "https://unicode.org/Public/MAPPINGS/ISO8859/8859-*.TXT"
    and "https://unicode.org/Public/MAPPINGS/VENDORS/MICSFT/WINDOWS/CP125*.TXT" */
/*
    libzint - the open source barcode library
    Copyright (C) 2021-2022 Robin Stuart <rstuart114@gmail.com>

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions
    are met:

    1. Redistributions of source code must retain the above copyright
       notice, this list of conditions and the following disclaimer.
    2. Redistributions in binary form must reproduce the above copyright
       notice, this list of conditions and the following disclaimer in the
       documentation and/or other materials provided with the distribution.
    3. Neither the name of the project nor the names of its contributors
       may be used to endorse or promote products derived from this software
       without specific prior written permission.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
    ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
    IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
    ARE DISCLAIMED.  IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE
    FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
    DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
    OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
    HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
    LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
    OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
    SUCH DAMAGE.
 */
/* SPDX-License-Identifier: BSD-3-Clause */

#ifndef Z_ECI_SB_H
#define Z_ECI_SB_H
EOD;

$out = explode("\n", $head);

$u_iso8859 = <<<'EOD'

/* Forward reference to base ISO/IEC 8859 routine - see "eci.c" */
static int u_iso8859(const unsigned int u, const unsigned short *tab_s, const unsigned short *tab_u,
            const unsigned char *tab_sb, int e, unsigned char *dest);
EOD;

$out = array_merge($out, explode("\n", $u_iso8859));

$iso8859_comments = array(
    array(), array(), // 0-1
    //    ECI    Description
    array( '4', 'Latin alphabet No. 2 (Latin-2)' ),
    array( '5', 'Latin alphabet No. 3 (Latin-3) (South European)' ),
    array( '6', 'Latin alphabet No. 4 (Latin-4) (North European)' ),
    array( '7', 'Latin/Cyrillic' ),
    array( '8', 'Latin/Arabic' ),
    array( '9', 'Latin/Greek' ),
    array( '10', 'Latin/Hebrew' ),
    array( '11', 'Latin alphabet No. 5 (Latin-5) (Latin/Turkish)' ),
    array( '12', 'Latin alphabet No. 6 (Latin-6) (Nordic)' ),
    array( '13', 'Latin/Thai' ),
    array(),
    array( '15', 'Latin alphabet No. 7 (Latin-7) (Baltic Rim)' ),
    array( '16', 'Latin alphabet No. 8 (Latin-8) (Celtic)' ),
    array( '17', 'Latin alphabet No. 9 (Latin-9)' ),
    array( '18', 'Latin alphabet No. 10 (Latin-10) (South-Eastern European)' ),
);

// Read the 8859 files.

$tot_8859 = 0;
for ($k = 2; $k <= 16; $k++) {
    if ($k == 12) continue;

    $file = $data_dirname . '/' . '8859-' . $k . '.TXT';

    if (($get = file_get_contents($file)) === false) {
        error_log($error = "$basename: ERROR: Could not read mapping file \"$file\"");
        exit($error . PHP_EOL);
    }

    $lines = explode("\n", $get);

    // Parse the file.

    $sort = array();
    $sb = array();
    $same = array();
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strncmp($line, '0x', 2) !== 0 || strpos($line, "*** NO MAPPING ***") !== false) {
            continue;
        }
        $matches = array();
        if (preg_match('/^0x([0-9A-F]{2})[ \t]+0x([0-9A-F]{4})[ \t].*$/', $line, $matches)) {
            $mb = hexdec($matches[1]);
            $unicode = hexdec($matches[2]);
            if ($unicode >= 0xA0) {
                if ($unicode <= 0xFF && $unicode == $mb) {
                    $same[] = $mb;
                } else {
                    $sort[] = $unicode;
                    $sb[] = $mb;
                }
            }
        }
    }

    sort($same);
    array_multisort($sort, $sb);

    $s = array( 0, 0, 0, 0, 0, 0 );
    for ($i = 0, $cnt = count($same); $i < $cnt; $i++) {
        $v = $same[$i] - 0xA0;
        $j = $v >> 4;
        $s[$j] |= 1 << ($v & 0xF);
    }

    // Output.

    $out[] = '';
    $out[] = '/* Tables for ISO/IEC 8859-' . $k . ' */';
    $out[] = 'static const unsigned short iso8859_' . $k . '_s[6] = { /* Straight-thru bit-flags */';
    $line = '   ';
    for ($i = 0; $i < 6; $i++) {
        $line .= sprintf(" 0x%04X,", $s[$i]);
    }
    $out[] = $line;
    $out[] = '};';
    $tot_8859 += 6 * 2;

    $cnt = count($sort);
    $out[] = 'static const unsigned short iso8859_' . $k . '_u[' . $cnt . '] = { /* Unicode codepoints sorted */';
    $line = '   ';
    for ($i = 0; $i < $cnt; $i++) {
        if ($i && $i % 8 === 0) {
            $out[] = $line;
            $line = '   ';
        }
        $line .= sprintf(' 0x%04X,', $sort[$i]);
    }
    if ($line !== '   ') {
        $out[] = $line;
    }
    $out[] = '};';
    $tot_8859 += $cnt * 2;

    $cnt = count($sb);
    $out[] = 'static const unsigned char iso8859_' . $k . '_sb[' . $cnt . '] = { /* Single-byte in Unicode order */';
    $line = ' ';
    for ($i = 0; $i < $cnt; $i++) {
        if ($i && $i % 8 === 0) {
            $out[] = $line;
            $line = ' ';
        }
        $line .= sprintf('   0x%02X,', $sb[$i]);
    }
    if ($line !== '   ') {
        $out[] = $line;
    }
    $out[] = '};';
    $tot_8859 += $cnt;

    $out[] = '';
    $out[] = '/* ECI ' . $iso8859_comments[$k][0] . ' ISO/IEC 8859-' . $k . ' ' . $iso8859_comments[$k][1] . ' */';
    $out[] = 'static int u_iso8859_' . $k . '(const unsigned int u, unsigned char *dest) {';
    $out[] = '    return u_iso8859(u, iso8859_' . $k . '_s, iso8859_' . $k . '_u, iso8859_' . $k . '_sb, ARRAY_SIZE(iso8859_' . $k . '_u) - 1, dest);';
    $out[] = '}';
}

if (0) {
    $out[] = '';
    $out[] = '/* Total ISO/IEC 8859 bytes: ' . $tot_8859 . ' */';
}

$u_cp125x = <<<'EOD'

/* Forward reference to base Windows-125x routine - see "eci.c" */
static int u_cp125x(const unsigned int u, const unsigned short *tab_s, const unsigned short *tab_u,
            const unsigned char *tab_sb, int e, unsigned char *dest);
EOD;

$out = array_merge($out, explode("\n", $u_cp125x));

$cp125x_comments = array(
    //    ECI    Description
    array( '21', 'Latin 2 (Central Europe)' ),
    array( '22', 'Cyrillic' ),
    array( '23', 'Latin 1' ),
    array(), array(), array(),
    array( '24', 'Arabic' ),
);

// Read the Windows 125x files.

$tot_cp125x = 0;
for ($k = 0; $k <= 6; $k++) {
    if ($k >= 3 && $k <= 5) continue;

    $file = $data_dirname . '/' . 'CP125' . $k . '.TXT';

    if (($get = file_get_contents($file)) === false) {
        error_log($error = "$basename: ERROR: Could not read mapping file \"$file\"");
        exit($error . PHP_EOL);
    }

    $lines = explode("\n", $get);

    // Parse the file.

    $sort = array();
    $sb = array();
    $same = array();
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strncmp($line, '0x', 2) !== 0 || strpos($line, "*** NO MAPPING ***") !== false) {
            continue;
        }
        $matches = array();
        if (preg_match('/^0x([0-9A-F]{2})[ \t]+0x([0-9A-F]{4})[ \t].*$/', $line, $matches)) {
            $mb = hexdec($matches[1]);
            $unicode = hexdec($matches[2]);
            if ($unicode >= 0x80) {
                if ($unicode <= 0xFF && $unicode == $mb) {
                    $same[] = $mb;
                } else {
                    $sort[] = $unicode;
                    $sb[] = $mb;
                }
            }
        }
    }

    sort($same);
    array_multisort($sort, $sb);

    $s = array( 0, 0, 0, 0, 0, 0 );
    for ($i = 0, $cnt = count($same); $i < $cnt; $i++) {
        $v = $same[$i] - 0xA0;
        $j = $v >> 4;
        $s[$j] |= 1 << ($v & 0xF);
    }

    // Output.

    $out[] = '';
    $out[] = '/* Tables for Windows 125' . $k . ' */';
    $out[] = 'static const unsigned short cp125' . $k . '_s[6] = { /* Straight-thru bit-flags */';
    $line = '   ';
    for ($i = 0; $i < 6; $i++) {
        $line .= sprintf(" 0x%04X,", $s[$i]);
    }
    $out[] = $line;
    $out[] = '};';
    $tot_cp125x += 6 * 2;

    $cnt = count($sort);
    $out[] = 'static const unsigned short cp125' . $k . '_u[' . $cnt . '] = { /* Unicode codepoints sorted */';
    $line = '   ';
    for ($i = 0; $i < $cnt; $i++) {
        if ($i && $i % 8 === 0) {
            $out[] = $line;
            $line = '   ';
        }
        $line .= sprintf(' 0x%04X,', $sort[$i]);
    }
    if ($line !== '   ') {
        $out[] = $line;
    }
    $out[] = '};';
    $tot_cp125x += $cnt * 2;

    $cnt = count($sb);
    $out[] = 'static const unsigned char cp125' . $k . '_sb[' . $cnt . '] = { /* Single-byte in Unicode order */';
    $line = ' ';
    for ($i = 0; $i < $cnt; $i++) {
        if ($i && $i % 8 === 0) {
            $out[] = $line;
            $line = ' ';
        }
        $line .= sprintf('   0x%02X,', $sb[$i]);
    }
    if ($line !== '   ') {
        $out[] = $line;
    }
    $out[] = '};';
    $tot_cp125x += $cnt;

    $out[] = '';
    $out[] = '/* ECI ' . $cp125x_comments[$k][0] . ' Windows-125' . $k . ' ' . $cp125x_comments[$k][1] . ' */';
    $out[] = 'static int u_cp125' . $k . '(const unsigned int u, unsigned char *dest) {';
    $out[] = '    return u_cp125x(u, cp125' . $k . '_s, cp125' . $k . '_u, cp125' . $k . '_sb, ARRAY_SIZE(cp125' . $k . '_u) - 1, dest);';
    $out[] = '}';
}

if (0) {
    $out[] = '';
    $out[] = '/* Total Windows 125x bytes: ' . $tot_cp125x . ' */';

    $out[] = '';
    $out[] = '/* Total bytes: ' . ($tot_8859 + $tot_cp125x) . ' */';
}

$out[] = '';
$out[] = '#endif /* Z_ECI_SB_H */';

file_put_contents($out_dirname . '/eci_sb.h', implode("\n", $out) . "\n");

/* vim: set ts=4 sw=4 et : */
