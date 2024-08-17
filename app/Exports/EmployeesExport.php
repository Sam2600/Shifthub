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

        $id_level_career_not_null = request()->employee_id && request()->career && request()->level;

        $level_career_not_null = request()->level && request()->career;
        $id_level_not_null = request()->level && request()->employee_id;
        $id_career_not_null = request()->employee_id && request()->career;

        $only_level_not_null = request()->level;
        $only_career_not_null = request()->career;
        $only_id_not_null = request()->employee_id && !request()->career && !request()->level;

        //This condition is special condition when i click search button without inputting anything, Because I made the default option's value is as zero for reasonable condition
        $id_level_carrer_all_null = !request()->level && !request()->career && !request()->employee_id;


        // When my 1st when condition is true it will run the query and skip all the other. If not second condition is will be check... if all of when conditions is not true ->paginate(2) will be proceed

        if ($id_level_carrer_all_null) {

            $employees = Employee::query()->select('id', 'employee_id', 'name', 'phone', 'email', 'nrc', 'career', 'level', 'dateOfBirth', 'address')->orderBy('updated_at', 'desc')->paginate(4);

            // Map the collection and modify the career field and level field
            $modifiedEmployees = $employees->map(function ($employee) {


                switch ($employee->career) {

                    case 'Frontend':
                        $employee->career = "Frontend";
                        break;

                    case 'Backend':
                        $employee->career = "Backend";
                        break;

                    case 'Fullstack':
                        $employee->career = "Fullstack";
                        break;

                    default:
                        $employee->career = "Mobile";
                        break;
                }

                switch ($employee->level) {

                    case 'Beginner':
                        $employee->level = "Beginner";
                        break;

                    case 'Junior Engineer':
                        $employee->level = "Junior Engineer";
                        break;

                    case 'Engineer':
                        $employee->level = "Engineer";
                        break;

                    default:
                        $employee->level = "Senior Engineer";
                        break;
                }

                return $employee;
            });

            //dd($modifiedEmployees);
            return $modifiedEmployees;
        }

        $employees = Employee::query()->select('id', 'employee_id', 'name', 'phone', 'email', 'nrc', 'career', 'level', 'dateOfBirth', 'address')->orderBy('updated_at', 'desc')

            ->when($id_level_career_not_null, function ($query) {

                $query->where('employee_id', 'LIKE', '%' . request()->get('employee_id') . '%')
                    ->where('career', '=', request()->get('career'))
                    ->where('level', '=', request()->get('level'));
            })

            ->when($level_career_not_null, function ($query) {

                $query->where('level', '=', request()->get('level'))
                    ->where('career', '=', request()->get('career'));
            })

            ->when($id_level_not_null, function ($query) {

                $query->where('level', '=', request()->get('level'))
                    ->where('employee_id', 'LIKE', '%' . request()->get('employee_id') . '%');
            })

            ->when($id_career_not_null, function ($query) {

                $query->where('employee_id', 'LIKE', '%' . request()->get('employee_id') . '%')
                    ->where('career', '=', request()->get('career'));
            })

            ->when($only_level_not_null, function ($query) {

                $query->where('employee_id', 'LIKE', '%' . request()->get('employee_id') . '%');
            })

            ->when($only_career_not_null, function ($query) {

                $query->where('level', '=', request()->get('level'));
            })

            ->when($only_id_not_null, function ($query) {

                $query->where('career', '=', request()->get('career'));
            })

            // if all of conditions above are wrong, this parigante will do the job..
            ->paginate(2);

        // Map the collection and modify the career field and level field
        $modifiedEmployees = $employees->map(function ($employee) {

            switch ($employee->career) {

                case 'Frontend':
                    $employee->career = "Frontend";
                    break;

                case 'Backend':
                    $employee->career = "Backend";
                    break;

                case 'Fullstack':
                    $employee->career = "Fullstack";
                    break;

                default:
                    $employee->career = "Mobile";
                    break;
            }

            switch ($employee->level) {

                case 'Beginner':
                    $employee->level = "Beginner";
                    break;

                case 'Junior Engineer':
                    $employee->level = "Junior Engineer";
                    break;

                case 'Engineer':
                    $employee->level = "Engineer";
                    break;

                default:
                    $employee->level = "Senior Engineer";
                    break;
            }

            return $employee;
        });

        //dd($modifiedEmployees);
        return $modifiedEmployees;
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
