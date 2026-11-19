-- Category for department activities (CPD Activities / Special Events), set from the public update-activity form.
ALTER TABLE `department_activities`
  ADD COLUMN `category` VARCHAR(50) NULL AFTER `title`;
