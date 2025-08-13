<?php

namespace Tests\Feature;

use App\Models\MonHoc;
use App\Models\Khoa;
use App\Models\GiaoVien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Firebase\JWT\JWT;

class MonHocTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Tạo user và token JWT
        $this->user = User::factory()->create([
            'role' => 'admin'
        ]);
        
        $payload = [
            'iss' => 'AcademicService',
            'sub' => $this->user->id,
            'iat' => time(),
            'exp' => time() + 3600,
        ];
        
        $this->token = JWT::encode($payload, config('jwt.secret'), 'HS256');
    }

    public function test_can_get_all_monhocs()
    {
        // Tạo dữ liệu test
        $khoa = Khoa::create(['ten_khoa' => 'Công nghệ thông tin']);
        $giaoVien = GiaoVien::create([
            'user_id' => $this->user->id,
            'ho_ten' => 'Nguyễn Văn A',
            'khoa_id' => $khoa->id
        ]);
        
        MonHoc::create([
            'ten_mon' => 'Lập trình C',
            'so_tin_chi' => 3,
            'khoa_id' => $khoa->id,
            'giao_vien_id' => $giaoVien->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->get('/api/monhocs');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        '*' => ['id', 'ten_mon', 'so_tin_chi', 'khoa_id', 'giao_vien_id']
                    ]
                ]);
    }

    public function test_can_create_monhoc()
    {
        $khoa = Khoa::create(['ten_khoa' => 'Công nghệ thông tin']);
        $giaoVien = GiaoVien::create([
            'user_id' => $this->user->id,
            'ho_ten' => 'Nguyễn Văn A',
            'khoa_id' => $khoa->id
        ]);

        $data = [
            'ten_mon' => 'Lập trình Java',
            'so_tin_chi' => 4,
            'khoa_id' => $khoa->id,
            'giao_vien_id' => $giaoVien->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->post('/api/monhocs', $data);

        $response->assertStatus(201)
                ->assertJson([
                    'status' => 'success',
                    'message' => 'Tạo môn học thành công'
                ]);

        $this->assertDatabaseHas('mon_hocs', $data);
    }

    public function test_can_update_monhoc()
    {
        $khoa = Khoa::create(['ten_khoa' => 'Công nghệ thông tin']);
        $giaoVien = GiaoVien::create([
            'user_id' => $this->user->id,
            'ho_ten' => 'Nguyễn Văn A',
            'khoa_id' => $khoa->id
        ]);
        
        $monHoc = MonHoc::create([
            'ten_mon' => 'Lập trình C',
            'so_tin_chi' => 3,
            'khoa_id' => $khoa->id,
            'giao_vien_id' => $giaoVien->id
        ]);

        $updateData = [
            'ten_mon' => 'Lập trình C nâng cao',
            'so_tin_chi' => 4,
            'khoa_id' => $khoa->id,
            'giao_vien_id' => $giaoVien->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->put("/api/monhocs/{$monHoc->id}", $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'message' => 'Cập nhật môn học thành công'
                ]);

        $this->assertDatabaseHas('mon_hocs', $updateData);
    }

    public function test_can_delete_monhoc()
    {
        $khoa = Khoa::create(['ten_khoa' => 'Công nghệ thông tin']);
        $giaoVien = GiaoVien::create([
            'user_id' => $this->user->id,
            'ho_ten' => 'Nguyễn Văn A',
            'khoa_id' => $khoa->id
        ]);
        
        $monHoc = MonHoc::create([
            'ten_mon' => 'Lập trình C',
            'so_tin_chi' => 3,
            'khoa_id' => $khoa->id,
            'giao_vien_id' => $giaoVien->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->delete("/api/monhocs/{$monHoc->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'message' => 'Xóa môn học thành công'
                ]);

        $this->assertDatabaseMissing('mon_hocs', ['id' => $monHoc->id]);
    }

    public function test_can_search_monhoc()
    {
        $khoa = Khoa::create(['ten_khoa' => 'Công nghệ thông tin']);
        $giaoVien = GiaoVien::create([
            'user_id' => $this->user->id,
            'ho_ten' => 'Nguyễn Văn A',
            'khoa_id' => $khoa->id
        ]);
        
        MonHoc::create([
            'ten_mon' => 'Lập trình C',
            'so_tin_chi' => 3,
            'khoa_id' => $khoa->id,
            'giao_vien_id' => $giaoVien->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->get('/api/monhocs-search?keyword=Lập trình');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data'
                ]);
    }
}
