<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class EmployeesExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithColumnWidths, WithStyles
{

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @author KaungHtetSan
     * @date 23/06/2023
     * @return array => query data of employees table
     */
    public function collection()
    {  

        //This process is to make the boolean conditions and run the ->when condition base on the conditions that i made and When input request are not null this step will do the work

        $idNotNullAndCareerAndLevelNotZero = request()->employee_id != null && request()->career != 0 && request()->level != 0;
        $levelAndCareerNotZero = request()->level != 0 && request()->career != 0;

        $levelNotZeroAndIdNotNull = request()->level != 0 && request()->employee_id != null;

        $idNotNullAndCareerNotZero = request()->employee_id != null && request()->career != 0;

        $idNotNullAndLevelCareerIsZero = request()->employee_id != null && request()->career == 0 && request()->level == 0;

        //dd($idNotNullAndLevelCareerIsZero);

        $levelIsNotZeroAndCareerIsZero = request()->level != 0;
        $careerIsNotZeroAndLevelIsZero = request()->career != 0;


        //This condition is special condition when i click search button without inputting anything, Because I made the default option's value is as zero for reasonable condition
        $CareerAndLevelAreZeroAndIdisNull = request()->level == 0 && request()->career == 0 && request()->employee_id == null;


        // When my 1st when condition is true it will run the query and skip all the other. If not second condition is will be check... if all of when conditions is not true ->paginate(2) will be proceed

        if ($CareerAndLevelAreZeroAndIdisNull) {

            $employees = Employee::query()->select('id', 'employee_id', 'name', 'phone', 'email', 'nrc', 'career_id', 'level_id', 'dateOfBirth', 'address')->where('deleted_at', null)->orderBy('updated_at', 'desc')->paginate(4);

            // Map the collection and modify the career_id field and level_id field
            $modifiedEmployees = $employees->map(function ($employee) {

                if ($employee->career_id == 1) {
                    $employee->career_id = "Frontend";
                }

                if ($employee->career_id == 2) {
                    $employee->career_id = "Backend";
                }

                if ($employee->career_id == 3) {
                    $employee->career_id = "FullStack";
                }

                if ($employee->career_id == 4) {
                    $employee->career_id = "Mobile";
                }

                if ($employee->level_id == 1) {
                    $employee->level_id = "Beginner Engineer";
                }

                if ($employee->level_id == 2) {
                    $employee->level_id = "Junior Engineer";
                }

                if ($employee->level_id == 3) {
                    $employee->level_id = "Engineer";
                }

                if ($employee->level_id == 4) {
                    $employee->level_id = "Senior Engineer";
                }

                return $employee;
            });

            //dd($modifiedEmployees);
            return $modifiedEmployees;
        }

        $employees = Employee::query()->select('id', 'employee_id', 'name', 'phone', 'email', 'nrc', 'career_id', 'level_id', 'dateOfBirth', 'address')->where('deleted_at', null)->orderBy('updated_at', 'desc')

            ->when($idNotNullAndCareerAndLevelNotZero, function ($query) {

                $query->where('employee_id', 'LIKE', '%' . request()->get('employee_id') . '%')
                    ->where('career_id', '=', request()->get('career'))
                    ->where('level_id', '=', request()->get('level'));
            })

            ->when($levelAndCareerNotZero, function ($query) {

                $query->where('level_id', '=', request()->get('level'))
                    ->where('career_id', '=', request()->get('career'));
            })

            ->when($levelNotZeroAndIdNotNull, function ($query) {

                $query->where('level_id', '=', request()->get('level'))
                ->where('employee_id', 'LIKE', '%' . request()->get('employee_id') . '%');
            })

            ->when($idNotNullAndCareerNotZero, function ($query) {

                $query->where('employee_id', 'LIKE', '%' . request()->get('employee_id') . '%')
                    ->where('career_id', '=', request()->get('career'));
            })

            ->when($idNotNullAndLevelCareerIsZero, function ($query) {

                $query->where('employee_id', 'LIKE', '%' . request()->get('employee_id') . '%');
            })

            ->when($levelIsNotZeroAndCareerIsZero, function ($query) {

                $query->where('level_id', '=', request()->get('level'));
            })

            ->when($careerIsNotZeroAndLevelIsZero, function ($query) {

                $query->where('career_id', '=', request()->get('career'));
            })

            // if all of conditions above are wrong, this parigante will do the job..
            ->paginate(4);

        // Map the collection and modify the career_id field and level_id field
        $modifiedEmployees = $employees->map(function ($employee) {

            if ($employee->career_id == 1) {
                $employee->career_id = "Frontend";
            }

            if ($employee->career_id == 2) {
                $employee->career_id = "Backend";
            }

            if ($employee->career_id == 3) {
                $employee->career_id = "FullStack";
            }

            if ($employee->career_id == 4) {
                $employee->career_id = "Mobile";
            }

            if ($employee->level_id == 1) {
                $employee->level_id = "Beginner Engineer";
            }

            if ($employee->level_id == 2) {
                $employee->level_id = "Junior Engineer";
            }

            if ($employee->level_id == 3) {
                $employee->level_id = "Engineer";
            }

            if ($employee->level_id == 4) {
                $employee->level_id = "Senior Engineer";
            }

            return $employee;
        });

        //dd($modifiedEmployees);
        return $modifiedEmployees;


        //return $employees;

        /*
        Old php pure style to paginate..

        // $limit = 3;

        // $page = request()->has('page') ? request()->input('page') : 1;

        // $start = ($page - 1) * $limit;

        // $employees = DB::table('employees')->skip($start)->limit($limit)->get();

        //return $employees;

        */
    }


    //Codes below are for excel sheet styles
    public function styles(Worksheet $sheet)
{
    // For Excel Column title background color and border
    $sheet->getStyle('A1:J1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('a4c639');
    $sheet->getStyle('A1:J1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // To make title texts in center vertically and horizontally
    $style = $sheet->getStyle('A1:J1');
    $style->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
        ->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getRowDimension(1)->setRowHeight(20);

    // To make table rows data in center vertically and horizontally
    $dataStyle = $sheet->getStyle('A2:J' . $sheet->getHighestRow());
    $dataStyle->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
        ->setVertical(Alignment::VERTICAL_CENTER);

    // Add borders to the table rows
    $borderStyle = [
        'borders' => [
            'outline' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
            ],
        ],
    ];
    $sheet->getStyle('A2:J' . $sheet->getHighestRow())->applyFromArray($borderStyle);

    // Title font
    $font = $style->getFont();
    $font->setBold(true);

    // Column border
    $columnBorder = [
        'borders' => [
            'outline' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
            ],
        ],
    ];
    
    $sheet->getStyle('A2:J' . $sheet->getHighestRow())->applyFromArray($columnBorder);
}



    // This is to make rows titles
    public function headings(): array
    {
        return [
            "ID",
            "Employee_id",
            "Name",
            "Phone",
            "Email",
            "NRC",
            "Career",
            "Level",
            "Date of Birth",
            "Address",
        ];
    }

    // This is to make column widths to adjust sizes manually
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 15,
            'C' => 20,
            'D' => 20,
            'E' => 30,
            'F' => 25,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 35,
        ];
    }

    public function title(): string
    {
        return 'Employee';
    }
}
