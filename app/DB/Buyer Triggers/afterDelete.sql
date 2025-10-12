DELIMITER $$

-- DELETE TRIGGER
CREATE TRIGGER trg_buyers_after_delete
AFTER DELETE ON buyers
FOR EACH ROW
BEGIN
    INSERT INTO audit_logs (
        table_name,
        row_id,
        action_type,
        old_data,
        new_data,
        row_hash_old,
        row_hash_new,
        db_user,
        changed_at
    ) VALUES (
        'buyers',
        OLD.byr_id,
        'DELETE',
        JSON_OBJECT(
            'byr_name', OLD.byr_name,
            'byr_type', OLD.byr_type,
            'byr_ntn_cnic', OLD.byr_ntn_cnic,
            'byr_address', OLD.byr_address,
            'byr_province', OLD.byr_province,
            'byr_logo', OLD.byr_logo,
            'byr_account_title', OLD.byr_account_title,
            'byr_account_number', OLD.byr_account_number,
            'byr_reg_num', OLD.byr_reg_num,
            'byr_contact_num', OLD.byr_contact_num,
            'byr_contact_person', OLD.byr_contact_person,
            'byr_IBAN', OLD.byr_IBAN,
            'byr_swift_code', OLD.byr_swift_code,
            'byr_acc_branch_name', OLD.byr_acc_branch_name,
            'byr_acc_branch_code', OLD.byr_acc_branch_code,
            'created_at', OLD.created_at,
            'updated_at', OLD.updated_at
        ),
        NULL,
        SHA2(CONCAT(
            OLD.byr_name,
            OLD.byr_type,
            OLD.byr_ntn_cnic,
            OLD.byr_address,
            OLD.byr_province,
            OLD.byr_logo,
            OLD.byr_account_title,
            OLD.byr_account_number,
            OLD.byr_reg_num,
            OLD.byr_contact_num,
            OLD.byr_contact_person,
            OLD.byr_IBAN,
            OLD.byr_swift_code,
            OLD.byr_acc_branch_name,
            OLD.byr_acc_branch_code,
            OLD.created_at,
            OLD.updated_at
        ), 256),
        NULL,
        CURRENT_USER(),
        NOW()
    );
END$$

DELIMITER ;
