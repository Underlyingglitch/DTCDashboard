<?php

namespace App\Http\Traits;

trait FunctionsTrait
{

    public function calculateGroupNrs($a)
    {
        $c = end($a);
        $d = key($a);
        $b = array($d => $c);
        array_pop($a);
        return array_merge($b, $a);
    }

    public function getGroups($n)
    {
        $x = [array_merge(range(1, $n), array_fill(0, (6 - $n < 0) ? 0 : 6 - $n, null))];
        for ($i = 0; $i < (($n < 6) ? 5 : $n - 1); $i++) {
            $x[] = $this->calculateGroupNrs($x[count($x) - 1]);
        }
        return $x;
    }

    public function getGroupNrs($n, $b)
    {
        $x = [array_merge(range($b * 10 + 1, $b * 10 + $n), array_fill(0, (6 - $n < 0) ? 0 : 6 - $n, null))];
        for ($i = 0; $i < (($n < 6) ? 5 : $n - 1); $i++) {
            $x[] = $this->calculateGroupNrs($x[count($x) - 1]);
        }
        return $x;
    }
}
