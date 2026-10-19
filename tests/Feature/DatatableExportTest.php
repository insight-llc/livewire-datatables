<?php

use Mediconesystems\LivewireDatatables\Exports\DatatableExport;
use PhpOffice\PhpSpreadsheet\IOFactory;

it('defaults the file name', function (): void {
    // 🧪 Arrange
    $export = new DatatableExport(collect());

    // 🧪 Act
    $fileName = $export->getFileName();

    // 🧪 Assert
    expect($fileName)->toBe('DatatableExport.xlsx');
});

it('honours a file name set after construction', function (): void {
    // 🧪 Arrange
    $export = (new DatatableExport(collect()))->setFileName('Custom.xlsx');

    // 🧪 Act
    $response = $export->download();

    // 🧪 Assert
    expect($export->getFileName())->toBe('Custom.xlsx');
    expect($response->headers->get('Content-Disposition'))->toContain('Custom.xlsx');
});

it('downloads the collection with headings and styles', function (): void {
    // 🧪 Arrange
    $export = (new DatatableExport(collect([
        ['Name' => 'Ada', 'Age' => 36],
        ['Name' => 'Alan', 'Age' => 41],
    ])))->setStyles([1 => ['font' => ['bold' => true]]]);

    // 🧪 Act
    $response = $export->download();
    $sheet = IOFactory::load($response->getFile()->getPathname())->getActiveSheet();

    // 🧪 Assert
    expect($response->headers->get('Content-Disposition'))->toContain('DatatableExport.xlsx');
    expect($sheet->toArray(null, false, false))->toBe([['Name', 'Age'], ['Ada', 36], ['Alan', 41]]);
    expect($sheet->getStyle('A1')->getFont()->getBold())->toBeTrue();
    expect($sheet->getStyle('A2')->getFont()->getBold())->toBeFalse();
});
