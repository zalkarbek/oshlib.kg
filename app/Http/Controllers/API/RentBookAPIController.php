<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\RentedBooks;
use App\Models\Reader;
use App\Repositories\ReaderRepository;
use Illuminate\Http\Request;

class RentBookAPIController extends AppBaseController
{
    /** @var ReaderRepository */
    private $readerRepository;

    public function __construct(
        ReaderRepository $readerRepository)
    {
        $this->readerRepository = $readerRepository;
    }

    public function createRent(Request $request)
    {
        $request->validate([
            'book_name' => 'required',
            'author_name' => 'required',
        ]);

        $input = $request->all();

        $user = $request->user();
        $reader = $user->reader;
        if (!$reader) {
            $request->validate([
                'reader_form' => 'required',
            ]);

            $reader = new Reader();
            $reader->user_id = $user->id;
        }
        if ($request->has('reader_form')) {
            $reader = $this->copyReader($input['reader_form'], $reader);
        }
        $reader->save();

        $rentedBook = new RentedBooks();
        // $rentedBook->inventory_number = $input['inventory_number'];
        // $rentedBook->department = $input['department'];
        $rentedBook->book_name = $input['book_name'];
        $rentedBook->author_name = $input['author_name'];
        $rentedBook->reader_id = $reader->id;
        if ($request->has('book_id')) {
            $rentedBook->book_id = $input['book_id'];
        }
        $rentedBook->save();

        return $this->sendResponse([
            'reader' => $reader,
            'rent' => $rentedBook,
        ], 'created successfully');
    }

    private function copyReader(array $input, Reader $reader)
    {
        $reader->name = $input['name'] ?? $reader->name;
        $reader->sur_name = $input['sur_name'] ?? $reader->sur_name;
        $reader->patronymic = $input['patronymic'] ?? $reader->patronymic;
        $reader->birth_date = $input['birth_date'] ?? $reader->birth_date;
        $reader->nationality = $input['nationality'] ?? $reader->nationality;
        $reader->work_place = $input['work_place'] ?? $reader->work_place;
        $reader->work_position = $input['work_position'] ?? $reader->work_position;
        $reader->home_address = $input['home_address'] ?? $reader->home_address;
        $reader->phone = $input['phone'] ?? $reader->phone;
        $reader->passport_id = $input['passport_id'] ?? $reader->passport_id;
        $reader->agreed_with_rules = $input['agreed_with_rules'] ?? $reader->agreed_with_rules;

        return $reader;
    }

    public function myRentedBooks(Request $request)
    {
        $rentedBooks = RentedBooks::where('reader_id', '=', $request->user()->reader->id)->with('book')->get();

        return $this->sendResponse($rentedBooks, 'Rent books retrieved successfully');
    }
}
