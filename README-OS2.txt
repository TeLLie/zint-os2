===================================================================================================
**** If you like the programs i'm porting and you want to donate to me, see the following URL: ****
**** http://www.bitwiseworks.com/shop/index.php?id_product=38&controller=product&id_lang=1     ****
===================================================================================================

Zint v2.16.0    


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

Welcome to Zint v2.16.0 port for OS/2.
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

Compiled now with Qt5 v5.15.1 GA

Version 2.16.0 (2025-12-19)
===========================

**Incompatible changes**
------------------------
- In `UNICODE_MODE`, ECI 899 Binary input now interpreted as UTF-8 (previously
  treated as-is, i.e. as binary bytes - this now requires `DATA_MODE`)
- Buffer length of member `errtxt` in `zint_symbol` extended 100 -> 160
  (client buffers may need checking/extending)
- New `content_segs` & `content_seg_count` fields in `zint_symbol` for use with
  new output option `BARCODE_CONTENT_SEGS`
- Symbol structure members `option_1`, `option_2` and `option_3` now updated
  after `ZBarcode_Encode()` and variants are called, and there are three new
  methods in the Qt Backend to access to them
- New Qt Backend method `isBindable()` for new flag `ZINT_CAP_BINDABLE`
- New Qt Backend methods `gs1SyntaxEngine()`, `setGS1SyntaxEngine()` and
  `haveGS1SyntaxEngine()` to access newly added GS1 Syntax Engine support
- GS1 Composites now return warning if CC type upped from requested due to size
  of composite data
- EAN-8 with add-on now returns warning that it's non-standard
- UPC-E now returns warning if first digit of 7 digits ignored (not '0' or '1')
- For GS1 Composite, no primary (linear component) now returns
  `ZINT_ERROR_INVALID_DATA` (previously returned `ZINT_ERROR_INVALID_OPTION`)
- The distributed Windows binary "zint.exe" is now built with Microsoft Visual
  Studio 2015 and requires the Visual C runtime DLL "VCRUNTIME140.dll"
  (previously it was built with Visual Studio 6.0)

Changes
-------
- Add new `BARCODE_CONTENT_SEGS` option for `output_options` which sets new
  fields `content_segs` and `content_seg_count` with encoded data
  (pre-converted, i.e. UTF-8 unless input mode is `DATA_MODE`)
- Add API funcs `ZBarcode_UTF8_To_ECI()` and `ZBarcode_Dest_Len_ECI()`
- Set `option_1`, `option_2`, `option_3` to values used in encodation, and add
  new access methods `encodedOption1()` etc. to Qt Backend, and use in GUI to
  provide better feedback
- AZTEC: give more precise warnings in low ECC situations, and indicate via
  `option_1` by setting to -1 (min 3 words), 0 (<5% + 3 words)
- Better warning messages on non-compliant heights
- composite: warn if CC type upped from requested
- gs1: csumalpha: improve warning, report both chars (ticket #332, props Harald
  Oehlmann)
- New `ZBarcode_Cap()` flag `ZINT_CAP_BINDABLE`, differentiated from
  `ZINT_CAP_STACKABLE`, and new Qt Backend method `isBindable()`
- DOTCODE: now pads rows if given number of columns instead of failing if rows
  below min (5)
- EAN-8 + add-on: warn as non-compliant
- UPC-E: warn if first digit of 7 (or 8 if check digit given) not '0' or '1'
- Extend `errtxt` buffer 100 -> 160
- Add new symbologies `BARCODE_EAN8`, `BARCODE_EAN_2ADDON`,
  `BARCODE_EAN_5ADDON`, `BARCODE_EAN13`, `BARCODE_EAN8_CC` and
  `BARCODE_EAN13_CC` as replacements for `BARCODE_EANX`, `BARCODE_EANX_CHK` and
  `BARCODE_EANX_CC` and use in CLI/GUI (`BARCODE_EANX` etc. marked as legacy)
- For EAN/UPC accept space as alternative add-on separator to '+', and accept
  GTIN-13 format with & without 2-digit or 5-digit add-on (no separator)
- GS1PARENS_MODE: allow parentheses in AI data if backslashed (necessary for
  opening parentheses, optional for closing ones)
- Prefix all `INTERNAL` funcs/tables with `zint_`, except for those in
  "backend/common.h", which are prefixed by `z_` - makes symbol clashes more
  unlikely when zint is statically linked (ticket #337, props Ulrich Becker)
- Add support for GS1 Syntax Engine with new `input_mode` flag
  `GS1SYNTAXENGINE_MODE` (CLI --gs1strict, GUI "GS1 Strict" checkbox)
- GS1_MODE: allow GS1 Digital Link URIs (no validation unless
  `GS1SYNTAXENGINE_MODE` set)
- CLI: --gs1parens, --gs1nocheck and --gs1strict now imply --gs1
- GS1: new AIs 717 (GSCN 25-199) and 8040-3 (GSCN 25-047)

Bugs
----
- CODABLOCKF: fix misencodation of extended ASCII 0xB0-0xB9 when followed by
  digit (ignore 2nd byte of FNC4 when categorizing Code C characters)
- AZTEC: fix GS1 mode with Structured Append (wasn't outputting initial FNC1)
- ECI: ECI 899 in UNICODE_MODE wasn't being converted from UTF-8, which was
  inconsistent
- set_height: fix non-compliance false positives by using epsilon in checks
- UPU_S10: fix Service Indicator warning re "H" (ticket #331, props Milton Neal)
- CLI: fix `separator` check to use new `ZINT_CAP_BINDABLE` instead of
  `ZINT_CAP_STACKABLE`
- ZBarcode_Cap: add missing symbologies to `ZINT_CAP_BINDABLE` (was
  `ZINT_CAP_STACKABLE`)
- MAILMARK_2D: fix postcode validation: no limited alphanumerics, spaced-out
  DPS "outward"-only allowed, all-blank DPS allowed (ticket #334, props Milton
  Neal)
- DOTCODE: fix padding allowance to cover cases with large no. of columns
  requested and little data, to prevent buffer overflow
- manual/man page: fix DATAMATRIX Sizes tables "28 12x26" -> "27 12x26"

