<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SecurePdf implements Rule
{
    public function passes($attribute, $value)
    {
        $originalName = $value->getClientOriginalName();
        $pathInfo = pathinfo($originalName);

        // 1. Multi extension
        if (substr_count($originalName, '.') > 1) {
            return false;
        }

        // 2. Suspicious pattern
        if (isset($pathInfo['filename']) && strpos($pathInfo['filename'], '.') !== false) {
            return false;
        }

        // 3. Dangerous extensions
        $dangerous = ['exe','bat','cmd','com','pif','scr','vbs','js','jar','php','asp','jsp'];
        $lower = strtolower($originalName);

        foreach ($dangerous as $ext) {
            if (str_contains($lower, '.' . $ext . '.')) {
                return false;
            }
        }

        // 4. Magic bytes PDF
        $handle = fopen($value->getPathname(), 'r');
        $header = fread($handle, 4);
        fclose($handle);

        if ($header !== '%PDF') {
            return false;
        }

        // 5. MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $value->getPathname());
        finfo_close($finfo);

        if ($mime !== 'application/pdf') {
            return false;
        }

        // 6. Scan JavaScript & OpenAction
        $content = file_get_contents($value->getPathname());

        if (preg_match('/\/JavaScript|\/JS/i', $content)) {
            return false;
        }

        if (preg_match('/\/OpenAction/i', $content)) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'File PDF tidak valid atau mengandung elemen berbahaya.';
    }
}
