<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>{{ $confirmOrder->confirm_order_no }}</title>
    <style>
        @page {
            margin: 90px 50px 70px 50px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1a1a1a;
            line-height: 1.5;
        }

        .header {
            position: fixed;
            top: -70px;
            left: 0;
            right: 0;
            height: 60px;
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 6px;
        }

        .header img {
            height: 40px;
        }

        .footer {
            position: fixed;
            bottom: -50px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #666;
        }

        h1.title {
            text-align: center;
            font-size: 15px;
            border-top: 1px solid #1a1a1a;
            border-bottom: 1px solid #1a1a1a;
            padding: 8px 0;
            margin: 0 0 4px 0;
        }

        p.subtitle {
            text-align: center;
            font-size: 11px;
            margin: 0 0 16px 0;
        }

        p.lead {
            margin: 0 0 8px 0;
        }

        table.ref-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        table.ref-table td {
            vertical-align: top;
            padding: 2px 4px;
            font-size: 10px;
        }

        table.ref-table td.label {
            width: 150px;
            font-weight: bold;
        }

        table.ref-table td.sep {
            width: 10px;
        }

        table.sow-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        table.sow-table th,
        table.sow-table td {
            border: 1px solid #1a1a1a;
            padding: 5px 8px;
            font-size: 10px;
            text-align: left;
        }

        table.sow-table th {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        table.sow-table td.fee {
            text-align: right;
            white-space: nowrap;
        }

        table.sow-table tr.total td {
            font-weight: bold;
            border-top: 2px solid #1a1a1a;
        }

        table.signature-table {
            width: 100%;
            margin-top: 24px;
            margin-bottom: 16px;
        }

        table.signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 10px;
            padding: 0 10px;
        }

        .signature-space {
            height: 55px;
        }

        .notes {
            margin-top: 8px;
        }

        .notes p.notes-title,
        .memo p.memo-title {
            font-weight: bold;
            margin: 0 0 4px 0;
        }

        .notes ol,
        .memo ol {
            margin: 0 0 12px 18px;
            padding: 0;
        }

        .notes ol li,
        .memo ol li {
            margin-bottom: 4px;
        }

        .memo-header {
            font-weight: bold;
            margin: 12px 0 4px 0;
        }

        .terms-page {
            page-break-before: always;
        }

        h2.terms-title {
            text-align: center;
            font-size: 13px;
            margin: 0 0 16px 0;
        }

        .term-section {
            margin-bottom: 10px;
        }

        .term-section .term-heading {
            font-weight: bold;
            margin-bottom: 4px;
        }

        table.term-items {
            width: 100%;
            border-collapse: collapse;
        }

        table.term-items td {
            padding: 0 0 5px 0;
            vertical-align: top;
            text-align: justify;
        }

        table.term-items td.term-number {
            width: 30px;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ $logoDataUri }}" alt="PT. Caputra Mitra Sejati">
    </div>

    <div class="footer">
        PT. Caputra Mitra Sejati &middot; {{ $confirmOrder->confirm_order_no }}
    </div>

    <h1 class="title">ORDER CONFIRMATION</h1>
    <p class="subtitle">{{ $confirmOrder->confirm_order_no }}</p>

    <p class="lead">Mengacu pada Penawaran Docking :</p>

    <table class="ref-table">
        <tr>
            <td class="label">Tanggal</td>
            <td class="sep">:</td>
            <td>{{ $confirmOrder->quotation->quotation_date?->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="label">No.</td>
            <td class="sep">:</td>
            <td>{{ $confirmOrder->quotation->quote_no }}</td>
        </tr>
        <tr>
            <td class="label">Project</td>
            <td class="sep">:</td>
            <td>{{ $confirmOrder->project->project_name }}</td>
        </tr>
        <tr>
            <td class="label">Perusahaan</td>
            <td class="sep">:</td>
            <td>{{ $confirmOrder->quotation->client->client_name ?: $confirmOrder->quotation->client->client_no }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td class="sep">:</td>
            <td>{{ trim(($confirmOrder->quotation->client->company_address_line_1 ?? '').' '.($confirmOrder->quotation->client->company_address_line_2 ?? '')) ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Contact Person</td>
            <td class="sep">:</td>
            <td>{{ $confirmOrder->quotation->clientContact?->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">No. Telp</td>
            <td class="sep">:</td>
            <td>{{ trim(($confirmOrder->quotation->client->client_phone_country_code ?? '').' '.($confirmOrder->quotation->client->client_phone_number ?? '')) ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jadwal Mulai Docking</td>
            <td class="sep">:</td>
            <td>{{ $confirmOrder->docking_start_date?->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="label">Pengenaan PPN</td>
            <td class="sep">:</td>
            <td>
                [{{ $confirmOrder->ppn ? 'X' : ' ' }}] PPN &nbsp;&nbsp;&nbsp;
                [{{ $confirmOrder->ppn ? ' ' : 'X' }}] NON PPN
            </td>
        </tr>
    </table>

    <table class="sow-table">
        <thead>
            <tr>
                <th>Scope of Work</th>
                <th style="width: 160px;">Fee</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($confirmOrder->scope_of_work as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="fee">Rp. {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td>TOTAL</td>
                <td class="fee">Rp. {{ number_format($confirmOrder->scope_of_work_total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <p class="lead">
        Demikian Order Confirmation ini kami buat, harap mendapat tindak lanjut dari pihak Bapak/Ibu.
        Terima kasih atas perhatian dan kerjasamanya.
    </p>

    <table class="signature-table">
        <tr>
            <td>Menyetujui,</td>
            <td>Hormat kami,</td>
        </tr>
        <tr>
            <td>{{ $confirmOrder->quotation->client->client_name ?: $confirmOrder->quotation->client->client_no }}</td>
            <td>PT. CAPUTRA MITRA SEJATI</td>
        </tr>
        <tr>
            <td class="signature-space"></td>
            <td class="signature-space"></td>
        </tr>
        <tr>
            <td>( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; )</td>
            <td>Amadin Alzanur</td>
        </tr>
        <tr>
            <td></td>
            <td>Head of Marketing</td>
        </tr>
    </table>

    <div class="notes">
        <p class="notes-title">Note :</p>
        <ol>
            @foreach ($notes as $note)
                <li>{{ $note }}</li>
            @endforeach
        </ol>
    </div>

    <div class="memo">
        <p class="memo-title">Catatan/Memo :</p>
        <p class="memo-header">Pembayaran : Mekanisme pembayaran sebagai berikut :</p>
        <ol>
            @foreach ($paymentTerms as $term)
                <li>{{ $term }}</li>
            @endforeach
        </ol>
    </div>

    <div class="terms-page">
        <h2 class="terms-title">SYARAT DAN KETENTUAN UMUM</h2>

        @foreach ($terms as $number => $section)
            <div class="term-section">
                <p class="term-heading">{{ $number }}. {{ $section['title'] }}</p>
                <table class="term-items">
                    @foreach ($section['items'] as $itemNumber => $item)
                        <tr>
                            <td class="term-number">{{ $itemNumber }}.</td>
                            <td>{{ $item }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endforeach
    </div>
</body>

</html>
