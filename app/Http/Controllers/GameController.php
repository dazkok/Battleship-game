<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function start()
    {
        if (!session()->has('user_session_id')) {
            return redirect()->route('home');
        }

        // Board settings
        $boardSize = 10;
        $ships = DB::table('ships')->get();

        if (!session()->has('board_id')) {
            $board = $this->createNewGame($ships, $boardSize);
        } else {
            $board = $this->loadGame($boardSize);
        }

        $variables = collect([
            'ships' => $ships,
            'board' => $board
        ]);

        return view('game.game', compact('variables'));
    }

    public function loadGame($boardSize)
    {
        $activeBoard = DB::table('board_squares')
                            ->select('bs_square_nr', 'bs_status', 'bs_ship_id')
                            ->where('bs_board_id', session()->get('board_id'))
                            ->get();
        
        $board = [];
        foreach ($activeBoard as $key => $square) {
            $row = preg_replace('/\d/', '', $square->bs_square_nr);
            $col = preg_replace('/[^0-9.]+/', '', $square->bs_square_nr);
            
            if (!isset($board[$row])) {
                $board[$row] = [];
            }

            $board[$row][$col] = [
                'status' => $square->bs_status,
                'ship_id' => $square->bs_ship_id
            ];
        }

        return $board;
    }

    public function createNewGame($ships, $boardSize = 10)
    {
        $board = $this->createEmptyBoard($boardSize);

        foreach ($ships as $ship) {
            for ($i = 0; $i < $ship->sh_count; $i++) {
                $this->placeShip($board, $ship->sh_size, $boardSize, random_int(1, 9999));
            }
        }

        $keys = range('a', 'j');
        $board = array_combine($keys, $board);
        $this->saveBoard($board);
        return $board;
    }

    // Function for creating an empty board
    public function createEmptyBoard($size = 10) 
    {
        $board = array_fill(0, $size, array_fill(0, $size, ['status' => 0, 'ship_id' => null]));

        return $board;
    }

    // Function for positioning a ship on the board
    public function placeShip(&$board, $shipSize, $boardSize, $shipId)
    {
        $orientation = rand(0, 1); // 0 - horizontally, 1 - vertically
        
        do {
            if ($orientation) {
                $startX = rand(0, $boardSize - 1);
                $startY = rand(0, $boardSize - $shipSize - 1);
            } else {
                $startX = rand(0, $boardSize - $shipSize - 1);
                $startY = rand(0, $boardSize - 1);
            }
            
            // Check if the ship can be located here
            $valid = true;
            for ($i = 0; $i < $shipSize; $i++) {
                if ($orientation) {
                    if ($board[$startY + $i][$startX]['status'] !== 0 || $this->hasAdjacentShip($board, $startY + $i, $startX)) {
                        $valid = false;
                        break;
                    }
                } else {
                    if ($board[$startY][$startX + $i]['status'] !== 0 || $this->hasAdjacentShip($board, $startY, $startX + $i)) {
                        $valid = false;
                        break;
                    }
                }
            }
        } while (!$valid);

        // Placement of the ship on the board
        for ($i = 0; $i < $shipSize; $i++) {
            if ($orientation) {
                $board[$startY + $i][$startX]['status'] = 1;
                $board[$startY + $i][$startX]['ship_id'] = $shipId;
            } else {
                $board[$startY][$startX + $i]['status'] = 1;
                $board[$startY][$startX + $i]['ship_id'] = $shipId;
            }
        }
    }

    private function hasAdjacentShip($board, $y, $x)
    {
        $adjacentCoordinates = [
            [$y - 1, $x],
            [$y + 1, $x],
            [$y, $x - 1],
            [$y, $x + 1],
            [$y - 1, $x - 1],
            [$y - 1, $x + 1],
            [$y + 1, $x - 1],
            [$y + 1, $x + 1]
        ];
    
        foreach ($adjacentCoordinates as list($adjY, $adjX)) {
            if ($adjY >= 0 && $adjY < count($board) && $adjX >= 0 && $adjX < count($board)) {
                if ($board[$adjY][$adjX]['status'] === 1) {
                    return true;
                }
            }
        }
    
        return false;
    }

    public function saveBoard($board)
    {
        $board_id = DB::table('boards')->insertGetId([
            'b_user_session_id' => session()->get('user_session_id')
        ]);

        session(['board_id' => $board_id]);
        $boardSquares = [];

        foreach ($board as $key => $row) {
            foreach ($row as $number => $square)
            $boardSquares[] = [
                'bs_board_id' => $board_id,
                'bs_square_nr' => $key . $number,
                'bs_status' => $square['status'],
                'bs_ship_id' => $square['ship_id']
            ];
        }

        DB::table('board_squares')->insert($boardSquares);
    }

    public function hitSquare(Request $request)
    {
        $boardId = session()->get('board_id');
        $squareNr = $request->input('squareNr');
        $square = DB::table('board_squares')
            ->select('bs_id', 'bs_square_nr', 'bs_status', 'bs_ship_id')
            ->where('bs_board_id', $boardId)
            ->where('bs_square_nr', $squareNr)
            ->first();

        if (!$square) {
            return response()->json(['error' => 'Invalid square number.']);
        }

        $squares = [];

        if ($square->bs_status === 0) {
            $this->updateSquareStatus($square->bs_id, 9);
            $squares[$squareNr] = $this->renderSquareView($squareNr, 9);
        } elseif ($square->bs_status === 1) {
            if ($this->isShipDestroyed($boardId, $square->bs_ship_id)) {
                $this->updateShipSquaresStatus($boardId, $square->bs_ship_id, 3);
                $destroyedShipSquares = $this->getDestroyedShipSquares($boardId, $square->bs_ship_id);
                foreach ($destroyedShipSquares as $shipSquare) {
                    $squares[$shipSquare->bs_square_nr] = $this->renderSquareView($shipSquare->bs_square_nr, 3);
                }
            } else {
                $this->updateSquareStatus($square->bs_id, 2);
                $squares[$squareNr] = $this->renderSquareView($squareNr, 2);
            }
        } else {
            return response()->json(['error' => 'Invalid square status.']);
        }

        return response()->json($squares);
    }

    public function isShipDestroyed($boardId, $shipId)
    {
        $shipCount = DB::table('board_squares')
            ->where('bs_board_id', $boardId)
            ->where('bs_ship_id', $shipId)
            ->where('bs_status', 1)
            ->count();

        return $shipCount <= 1;
    }

    private function updateSquareStatus($squareId, $status)
    {
        DB::table('board_squares')
            ->where('bs_id', $squareId)
            ->update([
                'bs_status' => $status
            ]);
    }

    private function updateShipSquaresStatus($boardId, $shipId, $status)
    {
        DB::table('board_squares')
            ->where('bs_ship_id', $shipId)
            ->where('bs_board_id', $boardId)
            ->update([
                'bs_status' => $status
            ]);
    }

    private function getDestroyedShipSquares($boardId, $shipId)
    {
        return DB::table('board_squares')
            ->where('bs_ship_id', $shipId)
            ->where('bs_board_id', $boardId)
            ->get();
    }

    private function renderSquareView($squareNr, $status)
    {
        $key = preg_replace('/\d/', '', $squareNr);
        $number = preg_replace('/[^0-9.]+/', '', $squareNr);
        $squareData = ['status' => $status];
        return view('game.square', compact('key', 'number', 'squareData'))->render();
    }

    public function getShotsCounter()
    {
        $shots = DB::table('board_squares')
            ->where('bs_board_id', session()->get('board_id'))
            ->where('bs_status', '>', 0)
            ->get();
        
        $shotsSuccessed = $shots->where('bs_status', 3)->count();
        $countAllShips = $shots->where('bs_status', '<', 4)->count();

        if ($countAllShips - $shotsSuccessed === 0) {
            $gameFinished = true;
            $this->endGame();
        } else {
            $gameFinished = false;
        }
        
        return response()->json(
            [
                'shotsCount' => $shots->where('bs_status', '>', 1)->count(), 
                'gameFinished' => $gameFinished
            ]
        );
    }

    public function endGame()
    {
        DB::table('boards')
            ->where('b_id', session()->get('board_id'))
            ->update(['b_ended' => 1]);

        session()->forget('board_id');
        return redirect()->route('home');
    }
}
