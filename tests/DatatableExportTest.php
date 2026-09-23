<?php

namespace Mediconesystems\LivewireDatatables\Tests;

use Mediconesystems\LivewireDatatables\Exports\DatatableExport;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DatatableExportTest extends TestCase
{
    /** @test */
    public function it_defaults_the_file_name()
    {
        $export = new DatatableExport(collect());

        $this->assertSame('DatatableExport.xlsx', $export->getFileName());
    }

    /** @test */
    public function it_honours_a_file_name_set_after_construction()
    {
        $export = (new DatatableExport(collect()))->setFileName('Custom.xlsx');

        $this->assertSame('Custom.xlsx', $export->getFileName());
        $this->assertStringContainsString(
            'Custom.xlsx',
            $export->download()->headers->get('Content-Disposition')
        );
    }

    /** @test */
    public function it_downloads_the_collection_with_headings_and_styles()
    {
        $export = (new DatatableExport(collect([
            ['Name' => 'Ada', 'Age' => 36],
            ['Name' => 'Alan', 'Age' => 41],
        ])))->setStyles([1 => ['font' => ['bold' => true]]]);

        $response = $export->download();

        $this->assertStringContainsString('DatatableExport.xlsx', $response->headers->get('Content-Disposition'));

        $sheet = IOFactory::load($response->getFile()->getPathname())->getActiveSheet();

        $this->assertSame([['Name', 'Age'], ['Ada', 36], ['Alan', 41]], $sheet->toArray(null, false, false));
        $this->assertTrue($sheet->getStyle('A1')->getFont()->getBold());
        $this->assertFalse($sheet->getStyle('A2')->getFont()->getBold());
    }
}
