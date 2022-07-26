/*
    Zint Barcode Generator - the open source barcode generator
    Copyright (C) 2009-2021 Robin Stuart <rstuart114@gmail.com>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/
/* vim: set ts=4 sw=4 et : */

#ifndef SEQUENCEWINDOW_H
#define SEQUENCEWINDOW_H

#include "ui_extSequence.h"
#include "barcodeitem.h"

class SequenceWindow : public QDialog, private Ui::SequenceDialog
{
    Q_OBJECT

public:
    SequenceWindow(BarcodeItem *bc);
    ~SequenceWindow();

private:
    QString apply_format(const QString &raw_number);

private slots:
    void clear_preview();
    void create_sequence();
    void check_generate();
    void from_file();
    void generate_sequence();

protected:
    BarcodeItem *m_bc;
};

#endif
