<?php

declare(strict_types=1);

/**
 * Design by: NodeX Studio
 * Telegram: @nodexstudio
 * Independent Validation Engine with Native Vietnamese Localization
 */

namespace NodeX\Validation;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Validation\ValidationException;
use NodeX\Database\Connection;
use Throwable;

class Validator
{
    private static ?ValidationFactory $factory = null;

    /**
     * Khởi tạo hoặc lấy instance của ValidationFactory
     */
    public static function getFactory(): ValidationFactory
    {
        if (self::$factory === null) {
            $loader = new ArrayLoader();

            // Bộ từ điển tiếng Việt tự nhiên cho toàn bộ các quy tắc xác thực
            $messages = [
                'accepted' => 'Trường :attribute phải được chấp nhận.',
                'active_url' => 'Trường :attribute không phải là một URL hợp lệ.',
                'after' => 'Trường :attribute phải là một ngày sau ngày :date.',
                'alpha' => 'Trường :attribute chỉ có thể chứa chữ cái.',
                'alpha_dash' => 'Trường :attribute chỉ có thể chứa chữ cái, số, dấu gạch ngang và dấu gạch dưới.',
                'alpha_num' => 'Trường :attribute chỉ có thể chứa chữ cái và số.',
                'array' => 'Trường :attribute phải là một mảng.',
                'before' => 'Trường :attribute phải là một ngày trước ngày :date.',
                'between' => [
                    'numeric' => 'Trường :attribute phải nằm trong khoảng :min đến :max.',
                    'file' => 'Dung lượng tập tin :attribute phải từ :min đến :max KB.',
                    'string' => 'Trường :attribute phải từ :min đến :max ký tự.',
                    'array' => 'Trường :attribute phải có từ :min đến :max phần tử.',
                ],
                'boolean' => 'Trường :attribute phải là true hoặc false.',
                'confirmed' => 'Xác nhận :attribute không khớp.',
                'date' => 'Trường :attribute không phải là định dạng ngày hợp lệ.',
                'different' => 'Trường :attribute và :other phải khác nhau.',
                'digits' => 'Trường :attribute phải gồm :digits chữ số.',
                'email' => 'Trường :attribute phải là địa chỉ email hợp lệ.',
                'exists' => 'Giá trị đã chọn cho :attribute không tồn tại.',
                'file' => 'Trường :attribute phải là một tập tin.',
                'image' => 'Trường :attribute phải là định dạng hình ảnh.',
                'in' => 'Giá trị đã chọn cho :attribute không hợp lệ.',
                'integer' => 'Trường :attribute phải là số nguyên.',
                'max' => [
                    'numeric' => 'Trường :attribute không được lớn hơn :max.',
                    'file' => 'Dung lượng :attribute không được vượt quá :max KB.',
                    'string' => 'Trường :attribute không được vượt quá :max ký tự.',
                    'array' => 'Trường :attribute không được có nhiều hơn :max phần tử.',
                ],
                'mimes' => 'Trường :attribute phải là tập tin kiểu: :values.',
                'min' => [
                    'numeric' => 'Trường :attribute phải tối thiểu là :min.',
                    'file' => 'Dung lượng :attribute tối thiểu phải là :min KB.',
                    'string' => 'Trường :attribute phải có ít nhất :min ký tự.',
                    'array' => 'Trường :attribute phải có tối thiểu :min phần tử.',
                ],
                'numeric' => 'Trường :attribute phải là một số.',
                'regex' => 'Định dạng trường :attribute không hợp lệ.',
                'required' => 'Trường :attribute không được để trống.',
                'same' => 'Trường :attribute và :other phải giống nhau.',
                'string' => 'Trường :attribute phải là một chuỗi ký tự.',
                'unique' => 'Trường :attribute này đã tồn tại trong hệ thống.',
                'url' => 'Trường :attribute không phải là định dạng URL hợp lệ.',
            ];

            $loader->addMessages('vi', 'validation', $messages);

            $translator = new Translator($loader, 'vi');
            $factory = new ValidationFactory($translator);

            // Kích hoạt kiểm tra cơ sở dữ liệu nếu Eloquent Capsule đang hoạt động
            try {
                $capsule = Connection::getCapsule();
                $dbManager = $capsule->getDatabaseManager();
                $verifier = new DatabasePresenceVerifier($dbManager);
                $factory->setPresenceVerifier($verifier);
            } catch (Throwable) {
                // Bỏ qua nếu chưa kết nối Database
            }

            self::$factory = $factory;
        }

        return self::$factory;
    }

    /**
     * Khởi tạo đối tượng Validator
     */
    public static function make(array $data, array $rules, array $messages = [], array $customAttributes = []): \Illuminate\Validation\Validator
    {
        return self::getFactory()->make($data, $rules, $messages, $customAttributes);
    }

    /**
     * Kiểm tra và trả về dữ liệu đã được validate hợp lệ, nếu sai ném ngoại lệ ValidationException
     */
    public static function validate(array $data, array $rules, array $messages = [], array $customAttributes = []): array
    {
        $validator = self::make($data, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
