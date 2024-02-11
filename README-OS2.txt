===================================================================================================
**** If you like the programs i'm porting and you want to donate to me, see the following URL: ****
**** http://www.bitwiseworks.com/shop/index.php?id_product=38&controller=product&id_lang=1     ****
===================================================================================================

Zint v2.13.0    


 CONTENTS OF THIS FILE
========================

1. INTRODUCTION

2. REQUIREMENTS

3. INSTALLATION

4. LICENSE, COPYRIGHT, DISCLAIMER

5. CONTACT

6. CREDITS

7. SUPPORT AND DONATIONS

8. HISTORY

9. RESTRICTIONS


1. INTRODUCTION
===============

Welcome to Zint v2.13.0 port for OS/2.
Zint is a suite of programs to allow easy encoding of data in any of the
wide range of public domain barcode standards and to allow integration of
this capability into your own programs.

2. REQUIREMENTS
===============
  The following requirements can be installed either by rpm or by zip files  
  except Extended System Tray widget which is currently available as zip only.
  

  RPM Installation (preferred):
  ============================
  klibc
  -----
    1. yum install libc
  
  GCC
  ----
    1. yum install libgcc1
    2. yum install libssp
    3. yum install libstdc++6 libstdc++
    4. yum install libsupc++6 libsupc++
    5. yum install libgcc-fwd
  
  Qt5 dll
  -------
    1. yum install QT5WDGT QT5GUI QT5WEBW QT5XML QT5NET QT5WEBC
    
  Zlib  
  ----
    1. yum install zlib
  
   pthread
  -------
    1. yum install pthread

  ZIP Installation:
  =================
  klibc    
  -----
    1. Download klibc 0.6.6 or better (see http://svn.netlabs.org/libc for 
        more information)
    2. Install the files to your libpath eg x:\usr\lib
  
  Qtcore5   
  -------
    1. Download Qt5 or better (see http://svn.netlabs.org/qt5 for more 
      information). 'Qt Runtime Libraries and Plugins' is sufficient.
    2. Install the files according to the readme
  
  Zlib   
  ----
    1. Download zlib from f.i. http://rpm.netlabs.org/release/00/zip/
    2. Unpack and install z.dll to your libpath eg. x:\usr\lib

  pthread
  -------
    1. Download pthread from f.i. http://rpm.netlabs.org/release/00/zip
    2. Unpack and install the dll to your libpath eg. x:\usr\lib

  Cups
  ----
  1. Go to eCUPS wiki to see how to install eCUPS (http://svn.netlabs.org/ecups)
  2. Install eCUPS according to the above wiki
  3. This is needed for printing, if it's not then it's not needed to install.


3. INSTALLATION
===============

When install manually Zint
------------------------------

  1. Create a directory for zint
  2. Extract the zint package to the new directory.
  3. Create a WPS object for zint-qt.exe
  4. Start zint-qt
  5. Enjoy the app
  


4. LICENSE, COPYRIGHT, DISCLAIMER
=================================

(C) 2007-2024 Robin Stuart <robin@zint.org.uk> http://www.zint.org.uk
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.



5. CONTACT
==========

if you find a bug, then add a ticket to the trac at http://svn.netlabs.org/qtapps
Only bug reports with a reproducable bug are accepted. :-)



6. CREDITS
==========

The port was done by: Elbert Pol aka TeLLie

Thanks go to:

  * Dmitry A. Kuminov
  * Silvan Scherrer
  * Robin Stuart

They either helped me when I had some nasty questions or did some testing for me.


7. SUPPORT AND DONATIONS
========================

Zint is based on volunteer work. If you would like to support further
development, you can do so in one of the following ways:


  * Donate to the Qt5 project: see qt.netlabs.org for more information

  * Contribute to the project: Besides actual development, this also includes
    maintaining the documentation and the project web site as well as help
    for users.


8. HISTORY
==========

Compiled now with Qt5 v5.12.1 GA

Version 2.13.0 (2023-12-18)
===========================

**Incompatible changes**
------------------------
- Buffer lengths of members `fgcolour` and `bgcolour` in `zint_symbol` extended
  10 -> 16 to allow for "C,M,Y,K" comma-separated decimal percentage strings
- CMYK values for EPS (slightly) and TIF (significantly) have changed - now use
  the same RGB -> CMYK formula
- Text (HRT) placement for vector (EMF/EPS/SVG) output changed - for EAN/UPC
  slightly further away from barcode, for all others slightly nearer. Some
  horizontal alignments of EAN/UPC vector text also tweaked
- Text (HRT) for standalone EAN-2 and EAN-5 now at top of symbol
  (was at bottom)
- Text height (font size) for SMALL_TEXT vector output reduced
- For Windows, filenames are now assumed to be UTF-8 encoded. Affects `outfile`
  in `zint_symbol` and all API filename arguments
- Never-used `fontsize` member removed from `zint_symbol`
- Buffer length of member `text` (HRT) in `zint_symbol` extended 128 -> 200
  (client buffers may need checking/extending)
- Font of text of SVG vector output now "OCRB, monospace" (EAN/UPC) or
  "Arimo, Arial, sans-serif" (all others)
  (was "Helvetica, sans-serif" for both)
- Unintended excess horizontal whitespace of Composite symbols removed, and
  quiet zone settings respected exactly, and centring of HRT (if any) now
  relative to linear part of symbol only rather than whole symbol
- Unlikely-to-be-used `bitmap_byte_length` member removed from `zint_symbol`
  (was only set on BMP output to length of BMP pixel array)
- EXCODE39 now defaults to displaying check digit in Human Readable Text (HRT)
- GS1_128 now warns if data > 48 (GS1 General Specifications max)

Changes
-------
- BMP/EMF/EPS/GIF/PCX/PNG/SVG/TIF/TXT: check for errors on writing to output
  file (ticket #275)
- manual/man page: document octal escape; Code 128 subset/mode -> Code Set
- Add special symbology-specific escape sequences (Code 128 only) for manual
  Code Set switching via `input_mode` flag `EXTRA_ESCAPE_MODE` (CLI --extraesc)
  (ticket #204)
- GUI: disable "Reset" colour if default; add "Unset" to Printing Scale dialog
  (allows unsetting of X-dim/resolution settings without having to zap)
- API/CLI/GUI: allow foreground/background colours to be specified as
  comma-separated decimal percentage strings "C,M,Y,K" where "C", "M" etc. are
  0-100 (ticket #281, 3rd point)
- PCX: add alpha support
- GUI: rearrange some Appearance tab inputs (Border Type <-> Width, Show Text
  <-> Font, Text/Font <-> Printing Scale/Size) to flow more naturally;
  save button "Save As..." -> "Save..." and add icon
- Add `text_gap` option to allow adjustment of vertical gap between barcode and
  text (HRT)
- DAFT: up max to 250 chars
- CLI: use own (Wine) version of `CommandLineToArgvW()` to avoid loading
  "shell32.dll"
- EAN/UPC: add quiet zone indicators option (API `output_options`
  `EANUPC_GUARD_WHITESPACE`, CLI `--guardwhitespace`) (ticket #287)
- EAN-2/EAN-5: HRT now at top instead of at bottom for standalones, following
  BWIPP
- EPS/SVG: use new `out_putsf()` func to output floats, avoiding trailing zeroes
  & locale dependency
- EPS: simplify "TR" formula
- SVG: change font from "Helvetica, sans-serif" to "OCRB, monospace" for EAN/UPC
  and "Arimo, Arial, sans-serif" for all others;
  use single "<path>" instead of multiple "<rect>"s to draw boxes (reduces file
  size)
- Add `EMBED_VECTOR_FONT` to `output_options` (CLI `--embedfont`) to enable
  embedding of font in vector output - currently only for SVG output
- GUI: use "OCRB" font for EAN/UPC and "Arimo" for all others (was "Helvetica"
  for both); add preview background colour option (default light grey) so as
  whitespace will show up in contrast (access via preview context menu)
- CODE128/common: add `ZINT_WARN_HRT_TRUNCATED` warning
- QRCODE: better assert(), removing a NOLINT (2 left)
- CLI: add some more barcode synonyms for DBAR
- CMake: don't include png.c unless ZINT_USE_PNG (avoids clang warning)
- vector: reduce SMALL_TEXT font height 6 -> 5 to be more like raster;
  reduce antialiasing allowance for `textoffset`;
  adjust text to baseline using values for Arimo rather than percentage
- manual: expand size/alpha details in Section "5.4 Buffering Symbols in Memory
  (raster)" (cf ticket #291); add BSD info
- EXCODE39: change to display check digit in HRT by default
- CODE39/EXCODE39/LOGMARS: new hidden check digit option
- GUI: move some symbology-specific options into Data Tab so separate tab
  unnecessary
- DATAMATRIX: add `DM_ISO_144` (--dmiso144) option for ISO placement of ECC
  codewords instead of default "de facto"
- manual: add annexes on Qt and Tcl backends
- CODE128: increase no. symbol chars max 60 -> 99
- frontend: truncate overlong `--primary` instead of ignoring
- man page: list size detail for matrix symbols (`--vers`)
- CODE11/C25XXX/CODE39/EXCODE39/HIBC_39/CODE93/CODABAR/PLESSEY/MSI_PLESSEY/FLAT/
  DAFT/TELEPEN/TELEPEN_NUM: increase allowed lengths
- API: add `ZBarcode_Reset()` to fully restore `zint_symbol` to default state

Bugs
----
- CEPNET: fix no HRT (library: `has_hrt()`)
- man page: fix Code 11 check digit info
- CMake: allow ctest to be run without having to install zint or manually set
  LD_LIBRARY_PATH and PATH (ticket #279, props Alexey Dokuchaev)
- GUI: fg/bgcolor text edit: fix right-click context menu not working properly
  by checking for it on FocusOut
- GUI: fix fg/bgcolor icon background not being reset on zap
- EMF/EPS/SVG/GUI: ignore BOLD_TEXT for EAN/UPC
- EMF/EPS/SVG: fix addon bars placement/length when text hidden
- For Windows, assume `outfile` & API filename args are in UTF-8,
  & use xxxW() APIs accordingly, ticket #288, props Marcel
  **Backwards-incompatible change**
- GUI: fix `save_to_file()` `filename.toLatin1()` -> `toUtf8()`
- CLI: batch mode: don't close input if stdin
- EAN/UPC: fix excess 1X to right of add-ons
- Composites: fix excess whitespace; fix quiet zone calcs to allow for linear
  shifting
- GUI: fix not enabling font combo "Small Bold (vector only)" by default
- CODEONE: fix S/T quiet zone 1X bottom (props BWIPP issue #245 doc)
- EAN-2/EAN-5: fix `BARCODE_BIND_TOP/BIND/BOX` output
- library: fix 21-bit Unicode conversion in `escape_char_process()`; fix
  restricting escaped data length by using de-escaped length to check
- AZTEC: fix out-of-bounds crash when user-specified size given, ticket #300,
  props Andre Maute; fix 4-layer compact block max (76 -> 64); fix encoding of
  byte-blocks > 11-bit limit
- CODABLOCKF: fix crash due to `columns` overflow, ticket #300, props Andre
  Maute
- CODEONE: fix out-of-bounds crash in `c1_c40text_cnt()` and looping on latch
  crash in `c1_encode()` and too small buffer for Version T, ticket #300, props
  Andre Maute
- EANX_CC/UPCA_CC: fix crash in `dbar_date()` on not checking length and crash
  in `gs1_verify()` on not checking length, ticket #300, props Andre Maute
- GS1_128_CC: fix divide-by-zero crash in `calc_padding_ccc()`, ticket #300,
  props Andre Maute
- HANXIN: fix incorrect numeric costings (out-by-1) in `hx_in_numeric()`, ticket
  #300 (#16), props Andre Maure
- PDF417: fix out-of-bounds crash in `pdf_text_submode_length()` and
  out-of-bounds crash on overrunning string and codeword buffers, ticket #300,
  props Andre Maute
- QRCODE: fix out-of-bounds crash due to incorrect mode costings for GS1
  percents in `qr_in_alpha()`; fix incorrect numeric costings (out-by-1) in
  `qr_in_numeric()`; ticket #300 (#14, #15; #16), props Andre Maute
