-- อัปเดต method สำหรับ payments ที่มี bank_id (โอนผ่านธนาคาร)
UPDATE payments 
SET method = 1 
WHERE bank_id IS NOT NULL 
  AND (method IS NULL OR method = 0);

-- อัปเดต method สำหรับ payments ที่ไม่มี bank_id (เงินสด/อื่นๆ)
UPDATE payments 
SET method = 2 
WHERE bank_id IS NULL 
  AND (method IS NULL OR method = 0);
