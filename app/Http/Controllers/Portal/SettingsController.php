<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        $keys = [
            'hero_headline', 'hero_subheadline', 'about_body',
            'programs_infant', 'programs_young_toddler', 'programs_toddler',
            'programs_preschool', 'notification_email',
        ];
        $content = collect($keys)->mapWithKeys(fn ($k) => [$k => SiteContent::get($k)]);
        return view('portal.settings.index', compact('content'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token', '_method') as $key => $value) {
            SiteContent::set($key, $value ?? '');
        }
        return back()->with('success', 'Settings saved.');
    }

    public function backup()
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $tableKey = 'Tables_in_' . $dbName;

        $sql = "-- Roberts Family ChildCare DB Backup\n-- " . now() . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $tableObj) {
            $table = $tableObj->$tableKey;

            $createResult = DB::select("SHOW CREATE TABLE `{$table}`");
            $createSql = $createResult[0]->{'Create Table'};
            $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $sql .= $createSql . ";\n\n";

            $rows = DB::table($table)->get();
            if ($rows->isNotEmpty()) {
                $sql .= "INSERT INTO `{$table}` VALUES\n";
                $values = $rows->map(function ($row) {
                    $escaped = collect((array) $row)->map(function ($v) {
                        if ($v === null) return 'NULL';
                        return "'" . addslashes($v) . "'";
                    })->join(', ');
                    return "({$escaped})";
                })->join(",\n");
                $sql .= $values . ";\n\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        return response($sql, 200, [
            'Content-Type'        => 'application/sql',
            'Content-Disposition' => 'attachment; filename="backup-' . now()->format('Y-m-d-His') . '.sql"',
        ]);
    }

    public function sql(Request $request)
    {
        $request->validate(['query' => 'required|string']);
        $query = trim($request->input('query'));

        try {
            $upper = strtoupper($query);
            if (str_starts_with($upper, 'SELECT') || str_starts_with($upper, 'SHOW') || str_starts_with($upper, 'DESCRIBE')) {
                $results = DB::select($query);
                return back()->with('sqlResults', $results)->with('sqlQuery', $query);
            } else {
                DB::statement($query);
                return back()->with('success', 'Query executed.')->with('sqlQuery', $query);
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->with('sqlQuery', $query);
        }
    }

    public function accounts()
    {
        $employees = Employee::orderBy('name')->get();
        return view('portal.settings.accounts', compact('employees'));
    }

    public function createAccount(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:255|unique:Employee,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:STAFF,ADMIN',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['isActive'] = true;
        $data['mustChangePassword'] = true;

        Employee::create($data);
        return back()->with('success', 'Account created.');
    }

    public function updateAccount(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);
        $data = $request->validate([
            'name'      => 'sometimes|string|max:100',
            'email'     => 'sometimes|email|max:255|unique:Employee,email,' . $id,
            'role'      => 'sometimes|in:STAFF,ADMIN',
            'isActive'  => 'sometimes|boolean',
            'password'  => 'sometimes|nullable|string|min:8',
        ]);

        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
            $data['mustChangePassword'] = true;
        } else {
            unset($data['password']);
        }

        $employee->update($data);
        return back()->with('success', 'Account updated.');
    }
}