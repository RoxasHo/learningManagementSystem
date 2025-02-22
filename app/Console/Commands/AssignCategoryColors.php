<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;

class AssignCategoryColors extends Command
{
    protected $signature = 'categories:assign-colors';
    protected $description = 'Assign random colors to categories';

    public function handle()
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            if (is_null($category->color)) {
                $category->color = $this->generateRandomColor();
                $category->save();
                $this->info("Assigned color {$category->color} to category {$category->name}");
            }
        }

        $this->info('Color assignment completed.');
    }

    private function generateRandomColor()
    {
        do {
            $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        } while ($this->isPaleColor($color));

        return $color;
    }

    private function isPaleColor($color)
    {
        $r = hexdec(substr($color, 1, 2));
        $g = hexdec(substr($color, 3, 2));
        $b = hexdec(substr($color, 5, 2));

        // Calculate the luminance
        $luminance = 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;

        // Return true if the color is pale (greater than a certain threshold)
        return $luminance > 200; // Adjust this value to your preference
    }
}
