<?php
/**
 * Academic departments under PMC.
 * Faculty sourced from faculty.php; intros/activities can be refined by each department.
 */
$academic_departments = [
  'anatomy' => [
    'name' => 'Anatomy',
    'icon' => 'bi-body-text',
    'group' => 'Basic',
    'intro' => [
      'The Department of Anatomy provides foundational knowledge of human structure essential to clinical practice. Through lectures, dissection, histology, and imaging correlation, students develop a clear understanding of gross and microscopic anatomy.',
      'Faculty emphasize applied anatomy relevant to surgery, medicine, and diagnostic specialties, preparing MBBS students for clinical years with strong morphological and spatial reasoning.',
    ],
    'hod' => 'Professor Dr. Jehanzeb Khan',
    'faculty' => [
      [
        'name' => 'Professor Dr. Jehanzeb Khan',
        'qualification' => 'MBBS, M.Phil',
        'reg' => '1780-N',
      ],
      [
        'name' => 'Professor Dr. Ihsan Ullah Wazir',
        'qualification' => 'MBBS, M.Phil',
        'reg' => '1887-N',
      ],
      [
        'name' => 'Professor Dr. Mohammad Saeed',
        'qualification' => 'MBBS, M.Phil',
        'reg' => '2131-N',
      ],
      [
        'name' => 'Professor Dr. Muhammad Imran Qureshi',
        'qualification' => 'MBBS, M.Phil',
        'reg' => '2205-N',
      ],
      [
        'name' => 'Professor Dr. Noman Ullah Wazir',
        'qualification' => 'MBBS, M.Phil, PhD',
        'reg' => '17344-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Mariya Azam Khattak',
        'qualification' => 'MBBS, M.Phil',
        'reg' => '19170-D',
      ],
    ],
    'activities' => [
      [
        'title' => 'Dissection Hall Sessions',
        'date' => 'Ongoing 2026',
        'text' => 'Structured cadaveric dissection and demonstration sessions for first-year MBBS students.',
      ],
      [
        'title' => 'Histology Practicals',
        'date' => 'Ongoing 2026',
        'text' => 'Microscopy-based learning of tissue structure aligned with the integrated curriculum.',
      ],
      [
        'title' => 'Clinical Correlation Seminars',
        'date' => '2025–26',
        'text' => 'Seminars linking anatomical concepts to clinical cases in surgery and radiology.',
      ],
    ],
  ],
  'physiology' => [
    'name' => 'Physiology',
    'icon' => 'bi-activity',
    'group' => 'Basic',
    'intro' => [
      'The Department of Physiology teaches normal body-system function — the foundation for understanding disease mechanisms and therapeutics in later clinical training.',
      'Practicals and tutorials reinforce cardiovascular, respiratory, renal, and neurophysiology concepts required for competent clinical reasoning.',
    ],
    'hod' => 'Professor Dr. Robina Riaz',
    'faculty' => [
      [
        'name' => 'Professor Dr. Robina Riaz',
        'qualification' => 'MBBS, M.Phil, MHPE, PhD',
        'reg' => '3929-N',
      ],
      [
        'name' => 'Professor Dr. Farzana Salman',
        'qualification' => 'MBBS, M.Phil, PhD',
        'reg' => '4576-N',
      ],
      [
        'name' => 'Professor Dr. Momina Haq',
        'qualification' => 'MBBS, M.Phil',
        'reg' => '20339-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Fatima Saadat',
        'qualification' => 'BDS, M.Phil',
        'reg' => '21384-D',
      ],
    ],
    'activities' => [
      [
        'title' => 'Structured Practical Classes',
        'date' => 'Ongoing 2026',
        'text' => 'Regular laboratory and tutorial sessions aligned with the MBBS curriculum.',
      ],
      [
        'title' => 'Assessment & Feedback Cycles',
        'date' => 'Term 2026',
        'text' => 'Formative quizzes, OSPE/spotting practice, and feedback to support student learning.',
      ],
      [
        'title' => 'Academic Seminars',
        'date' => '2025–26',
        'text' => 'Departmental seminars linking core concepts to clinical applications.',
      ],
    ],
  ],
  'biochemistry' => [
    'name' => 'Biochemistry',
    'icon' => 'bi-moisture',
    'group' => 'Basic',
    'intro' => [
      'The Department of Biochemistry covers molecular and metabolic processes essential to health, laboratory diagnostics, and clinical decision-making.',
      'Students learn biochemical pathways and their clinical relevance through lectures, practicals, and case-based discussions.',
    ],
    'hod' => 'Professor Dr. Sadia Haroon',
    'faculty' => [
      [
        'name' => 'Professor Dr. Sadia Haroon',
        'qualification' => 'MBBS, M.Phil',
        'reg' => '13072-P',
      ],
      [
        'name' => 'Professor Dr. Shamaila Wadud',
        'qualification' => 'MBBS, M.Phil, PhD',
        'reg' => '9330-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Sara Yar Khan',
        'qualification' => 'BDS, M.Phil',
        'reg' => '22465-D',
      ],
      [
        'name' => 'Assistant Professor Dr. Sikandar Ali Khan',
        'qualification' => 'MBBS, M.Phil',
        'reg' => '23145-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Structured Practical Classes',
        'date' => 'Ongoing 2026',
        'text' => 'Regular laboratory and tutorial sessions aligned with the MBBS curriculum.',
      ],
      [
        'title' => 'Assessment & Feedback Cycles',
        'date' => 'Term 2026',
        'text' => 'Formative quizzes, OSPE/spotting practice, and feedback to support student learning.',
      ],
      [
        'title' => 'Academic Seminars',
        'date' => '2025–26',
        'text' => 'Departmental seminars linking core concepts to clinical applications.',
      ],
    ],
  ],
  'pathology' => [
    'name' => 'Pathology',
    'icon' => 'bi-eyedropper',
    'group' => 'Basic',
    'intro' => [
      'The Department of Pathology bridges basic science and clinical practice through the study of disease mechanisms, morphology, haematology, microbiology, and chemical pathology.',
      'Teaching prepares students to interpret laboratory findings and understand the pathological basis of medicine.',
    ],
    'hod' => 'Professor Dr. Fozia Rauf',
    'faculty' => [
      [
        'name' => 'Professor Dr. Fozia Rauf',
        'qualification' => 'MBBS, FCPS (Histopathology)',
        'reg' => '9167-N',
      ],
      [
        'name' => 'Professor Dr. Mohsina Haq',
        'qualification' => 'MBBS, M.Phil (Microbiology), PhD',
        'reg' => '17218-N',
      ],
      [
        'name' => 'Professor Dr. Ambreen Gul',
        'qualification' => 'MBBS, M.Phil (Chemical Pathology)',
        'reg' => '11850-N',
      ],
      [
        'name' => 'Professor Dr. Mian Ihsanullah',
        'qualification' => 'MBBS, M.Phil (Chemical Pathology), DCP',
        'reg' => '475-N',
      ],
      [
        'name' => 'Professor Dr. Ashraf Khan',
        'qualification' => 'MBBS, M.Phil (Haematology)',
        'reg' => '2445-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Shabina Saifullah',
        'qualification' => 'MBBS, M.Phil (Chemical Pathology)',
        'reg' => '8611-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Sumira Abbas',
        'qualification' => 'MBBS, M.Phil (Haematology)',
        'reg' => '21012-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Sara Yousaf',
        'qualification' => 'MBBS, M.Phil (Histopathology)',
        'reg' => '80451-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Ashfaq Ahmad',
        'qualification' => 'MBBS, M.Phil (Microbiology)',
        'reg' => '25375-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Haseeba Arif',
        'qualification' => 'BDS, M.Phil (Microbiology)',
        'reg' => '18937-D',
      ],
    ],
    'activities' => [
      [
        'title' => 'Structured Practical Classes',
        'date' => 'Ongoing 2026',
        'text' => 'Regular laboratory and tutorial sessions aligned with the MBBS curriculum.',
      ],
      [
        'title' => 'Assessment & Feedback Cycles',
        'date' => 'Term 2026',
        'text' => 'Formative quizzes, OSPE/spotting practice, and feedback to support student learning.',
      ],
      [
        'title' => 'Academic Seminars',
        'date' => '2025–26',
        'text' => 'Departmental seminars linking core concepts to clinical applications.',
      ],
    ],
  ],
  'pharmacology' => [
    'name' => 'Pharmacology',
    'icon' => 'bi-capsule-pill',
    'group' => 'Basic',
    'intro' => [
      'The Department of Pharmacology focuses on mechanisms of drug action, therapeutics, adverse effects, and rational prescribing.',
      'Students develop safe prescribing habits aligned with clinical scenarios and essential medicine principles.',
    ],
    'hod' => 'Professor Dr. Shafaq Zafar',
    'faculty' => [
      [
        'name' => 'Professor Dr. Shafaq Zafar',
        'qualification' => 'MBBS, M.Phil, CHPE, PhD',
        'reg' => '14188-N',
      ],
      [
        'name' => 'Professor Dr. Riaz Nasim',
        'qualification' => 'MBBS, M.Phil',
        'reg' => '598-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Amber Javed',
        'qualification' => 'MBBS, M.Phil, CHPE',
        'reg' => '10512-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Erum Rehman',
        'qualification' => 'BDS, M.Phil, CHPE, CHR',
        'reg' => '18609-D',
      ],
      [
        'name' => 'Assistant Professor Dr. Muhammad Shahid',
        'qualification' => 'MBBS, M.Phil, CHPE',
        'reg' => '28805-N',
      ],
      [
        'name' => 'Lecturer Dr. Khadija Maryam',
        'qualification' => 'MBBS, CHPE, PGT Pharmacy',
        'reg' => '',
      ],
      [
        'name' => 'Lecturer Dr. Fahad Jan',
        'qualification' => 'MBBS, CHPE',
        'reg' => '',
      ],
      [
        'name' => 'Lecturer Dr. Rabiya',
        'qualification' => 'MBBS, CHPE',
        'reg' => '',
      ],
      [
        'name' => 'Lecturer Dr. Ameer Hamza',
        'qualification' => 'On leave',
        'reg' => '',
      ],
    ],
    'activities' => [
      [
        'title' => 'Structured Practical Classes',
        'date' => 'Ongoing 2026',
        'text' => 'Regular laboratory and tutorial sessions aligned with the MBBS curriculum.',
      ],
      [
        'title' => 'Assessment & Feedback Cycles',
        'date' => 'Term 2026',
        'text' => 'Formative quizzes, OSPE/spotting practice, and feedback to support student learning.',
      ],
      [
        'title' => 'Academic Seminars',
        'date' => '2025–26',
        'text' => 'Departmental seminars linking core concepts to clinical applications.',
      ],
    ],
  ],
  'forensic-medicine' => [
    'name' => 'Forensic Medicine',
    'icon' => 'bi-shield-check',
    'group' => 'Basic',
    'intro' => [
      'The Department of Forensic Medicine covers medico-legal responsibilities, injury interpretation, and the interface of medicine and law.',
      'Training prepares graduates for ethical and legal duties expected of medical practitioners.',
    ],
    'hod' => 'Professor Dr. Rubina Salma Yasmin',
    'faculty' => [
      [
        'name' => 'Professor Dr. Rubina Salma Yasmin',
        'qualification' => 'MBBS, MCPS, M.Phil',
        'reg' => '814-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Muhammad Bilal Khan',
        'qualification' => 'MBBS, DMJ',
        'reg' => '28264-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Muhammad Wasif',
        'qualification' => 'MBBS, M.Phil',
        'reg' => '30470-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Structured Practical Classes',
        'date' => 'Ongoing 2026',
        'text' => 'Regular laboratory and tutorial sessions aligned with the MBBS curriculum.',
      ],
      [
        'title' => 'Assessment & Feedback Cycles',
        'date' => 'Term 2026',
        'text' => 'Formative quizzes, OSPE/spotting practice, and feedback to support student learning.',
      ],
      [
        'title' => 'Academic Seminars',
        'date' => '2025–26',
        'text' => 'Departmental seminars linking core concepts to clinical applications.',
      ],
    ],
  ],
  'chs' => [
    'name' => 'Community Health Sciences',
    'icon' => 'bi-people-fill',
    'group' => 'Basic',
    'intro' => [
      'Community Health Sciences prepares students for population health, prevention, epidemiology, and community-oriented primary care.',
      'Field exposure and community placements connect classroom learning with real public-health needs in KP.',
    ],
    'hod' => 'Professor Dr. Mohammad Aman Khan',
    'faculty' => [
      [
        'name' => 'Professor Dr. Mohammad Aman Khan',
        'qualification' => 'MBBS, M.Phil, MCPS, DOMS, DCEH',
        'reg' => '1706-N',
      ],
      [
        'name' => 'Professor Dr. Saeed Anwar',
        'qualification' => 'MBBS, MPH, ADHPE',
        'reg' => '1960-N',
      ],
      [
        'name' => 'Professor Dr. Farhat R Malik',
        'qualification' => 'MBBS, MPH',
        'reg' => '7155-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Faqir Muhammad Anwar',
        'qualification' => 'MD, MPH',
        'reg' => '1282-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Aziza Alam',
        'qualification' => 'MBBS, M.Phil',
        'reg' => '7401-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Structured Practical Classes',
        'date' => 'Ongoing 2026',
        'text' => 'Regular laboratory and tutorial sessions aligned with the MBBS curriculum.',
      ],
      [
        'title' => 'Assessment & Feedback Cycles',
        'date' => 'Term 2026',
        'text' => 'Formative quizzes, OSPE/spotting practice, and feedback to support student learning.',
      ],
      [
        'title' => 'Academic Seminars',
        'date' => '2025–26',
        'text' => 'Departmental seminars linking core concepts to clinical applications.',
      ],
    ],
  ],
  'dhpe' => [
    'name' => 'DHPE & Research',
    'icon' => 'bi-mortarboard-fill',
    'group' => 'Basic',
    'intro' => [
      'The Department of Health Professions Education & Research supports teaching excellence, curriculum development, and educational scholarship across PMC.',
      'Faculty contribute to faculty development, assessment design, and research capacity building.',
    ],
    'hod' => 'Assistant Professor Dr. Neelofar Shaheen',
    'faculty' => [
      [
        'name' => 'Assistant Professor Dr. Neelofar Shaheen',
        'qualification' => 'MBBS, MHPE',
        'reg' => '13134-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Urooj Saleem',
        'qualification' => 'BDS, MHPE',
        'reg' => '10935-D',
      ],
      [
        'name' => 'Assistant Professor Dr. Uzma Siddique',
        'qualification' => 'BDS, MHPE',
        'reg' => '9198-D',
      ],
    ],
    'activities' => [
      [
        'title' => 'Structured Practical Classes',
        'date' => 'Ongoing 2026',
        'text' => 'Regular laboratory and tutorial sessions aligned with the MBBS curriculum.',
      ],
      [
        'title' => 'Assessment & Feedback Cycles',
        'date' => 'Term 2026',
        'text' => 'Formative quizzes, OSPE/spotting practice, and feedback to support student learning.',
      ],
      [
        'title' => 'Academic Seminars',
        'date' => '2025–26',
        'text' => 'Departmental seminars linking core concepts to clinical applications.',
      ],
    ],
  ],
  'medicine' => [
    'name' => 'Medicine',
    'icon' => 'bi-heart-pulse-fill',
    'group' => 'Clinical',
    'intro' => [
      'The Department of Medicine provides clinical training in internal medicine through ward work, outpatient clinics, and bedside teaching at affiliated hospitals.',
      'Students develop history-taking, examination, diagnostic reasoning, and patient-management skills under consultant supervision.',
    ],
    'hod' => 'Professor Dr. Muhammad Subhan',
    'faculty' => [
      [
        'name' => 'Professor Dr. Muhammad Subhan',
        'qualification' => 'MBBS, FCPS',
        'reg' => '2542-N',
      ],
      [
        'name' => 'Professor Dr. Najib Ul Haq',
        'qualification' => 'MBBS, MCPS, MRCP (UK)',
        'reg' => '1795-N',
      ],
      [
        'name' => 'Professor Dr. Jehanzeb Afridi',
        'qualification' => 'MBBS, FCPS',
        'reg' => '5101-N',
      ],
      [
        'name' => 'Professor Dr. Faridullah Shah',
        'qualification' => 'MBBS, FCPS',
        'reg' => '3190-N',
      ],
      [
        'name' => 'Professor Dr. Noor Mohammad',
        'qualification' => 'MBBS, FCPS',
        'reg' => '4053-N',
      ],
      [
        'name' => 'Associate Professor Dr. Bakht Biland Khan',
        'qualification' => 'MBBS, FCPS',
        'reg' => '6180-N',
      ],
      [
        'name' => 'Associate Professor Dr. Muhammad Abbas',
        'qualification' => 'MBBS, FCPS',
        'reg' => '14841-N',
      ],
      [
        'name' => 'Associate Professor Dr. Saima Mehboob',
        'qualification' => 'MBBS, FCPS',
        'reg' => '13297-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Muhammad Qasim',
        'qualification' => 'MBBS, FCPS',
        'reg' => '17354-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Abdul Samad',
        'qualification' => 'MBBS, Dip. Card',
        'reg' => '852-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Nafees Ahmad',
        'qualification' => 'MBBS, FCPS',
        'reg' => '14584-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Yasir Iqbal',
        'qualification' => 'MBBS, FCPS',
        'reg' => '29023-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'surgery' => [
    'name' => 'Surgery',
    'icon' => 'bi-scissors',
    'group' => 'Clinical',
    'intro' => [
      'The Department of Surgery trains students in surgical assessment, operative principles, and peri-operative care across affiliated teaching hospitals.',
      'Clinical rotations emphasize safe practice, communication, and early recognition of surgical emergencies.',
    ],
    'hod' => 'Professor Dr. Mohammad Tayeb',
    'faculty' => [
      [
        'name' => 'Professor Dr. Mohammad Tayeb',
        'qualification' => 'MBBS, FCPS',
        'reg' => '9016-N',
      ],
      [
        'name' => 'Professor Dr. Ijaz Ahmad',
        'qualification' => 'MBBS, FCPS',
        'reg' => '2237-N',
      ],
      [
        'name' => 'Professor Dr. Muzaffur Ud Din Sadiq',
        'qualification' => 'MBBS, FCPS',
        'reg' => '1831-N',
      ],
      [
        'name' => 'Associate Professor Dr. Sahibzada Salma Rahman',
        'qualification' => 'MBBS, FCPS',
        'reg' => '10023-N',
      ],
      [
        'name' => 'Associate Professor Dr. Sheikh Muhammad Ibqar Azeem',
        'qualification' => 'MBBS, FCPS',
        'reg' => '13791-N',
      ],
      [
        'name' => 'Associate Professor Dr. Ahmad Arsalan Tahir',
        'qualification' => 'MBBS, FCPS, FRCS (Edinburgh)',
        'reg' => '17595-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Shahid Ullah Ahmad',
        'qualification' => 'MBBS, FCPS',
        'reg' => '15170-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Waleed Mabood',
        'qualification' => 'MBBS, FCPS',
        'reg' => '23916-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Ambareen Subhan',
        'qualification' => 'MBBS, FCPS',
        'reg' => '20087-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Shoaib Muhammad',
        'qualification' => 'MBBS, FCPS',
        'reg' => '19009-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Asad Ullah Khan',
        'qualification' => 'MBBS, FCPS',
        'reg' => '18341-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'gynaecology' => [
    'name' => 'Gynaecology & Obstetrics',
    'icon' => 'bi-gender-female',
    'group' => 'Clinical',
    'intro' => [
      'Gynaecology & Obstetrics provides training in women’s health, antenatal care, labour-ward practice, and common gynaecological conditions.',
      'Students gain clinical exposure in teaching-hospital settings with a focus on respectful, evidence-based care.',
    ],
    'hod' => 'Professor Dr. Tehniyat Attiya Ur Razaq',
    'faculty' => [
      [
        'name' => 'Professor Dr. Tehniyat Attiya Ur Razaq',
        'qualification' => 'MBBS, FCPS',
        'reg' => '6459-N',
      ],
      [
        'name' => 'Professor Dr. Shahnaz Parveen',
        'qualification' => 'MBBS, MCPS, FCPS',
        'reg' => '1871-N',
      ],
      [
        'name' => 'Professor Dr. Samdana Wahab',
        'qualification' => 'MBBS, FCPS',
        'reg' => '7886-N',
      ],
      [
        'name' => 'Associate Professor Dr. Shamim Akhtar',
        'qualification' => 'MBBS, FCPS',
        'reg' => '43247-P',
      ],
      [
        'name' => 'Associate Professor Dr. Shandana Bawar',
        'qualification' => 'MBBS, FCPS, MRCOG',
        'reg' => '9071-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Farzana Burki',
        'qualification' => 'MBBS, FCPS',
        'reg' => '14305-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Robina Qadeer',
        'qualification' => 'MBBS, FCPS',
        'reg' => '13006-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Afrah Aman',
        'qualification' => 'MBBS, FCPS',
        'reg' => '17887-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Sobia Siraj',
        'qualification' => 'MBBS, FCPS',
        'reg' => '19166-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Asma Ghani',
        'qualification' => 'MBBS, FCPS',
        'reg' => '18364-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Sana Nafees',
        'qualification' => 'MBBS, FCPS',
        'reg' => '21041-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'paediatrics' => [
    'name' => 'Paediatrics',
    'icon' => 'bi-person-fill',
    'group' => 'Clinical',
    'intro' => [
      'The Department of Paediatrics focuses on child health, growth and development, neonatal care, and common paediatric illnesses.',
      'Hospital-based teaching builds competence in assessment and management of infants and children.',
    ],
    'hod' => 'Professor Dr. Saima Ali',
    'faculty' => [
      [
        'name' => 'Professor Dr. Saima Ali',
        'qualification' => 'MBBS, FCPS',
        'reg' => '7435-N',
      ],
      [
        'name' => 'Professor Dr. Shazia Aurangzeb',
        'qualification' => 'MBBS, FCPS',
        'reg' => '8628-N',
      ],
      [
        'name' => 'Professor Dr. Saffiullah',
        'qualification' => 'MBBS, MRCPCH (UK), MRCPS (Glasgow)',
        'reg' => '9141-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Hameed Ullah',
        'qualification' => 'MBBS, MRCPCH (UK)',
        'reg' => '9601-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Rabbia Shaheen',
        'qualification' => 'MBBS, FCPS',
        'reg' => '19131-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Sana Nafis',
        'qualification' => 'MBBS, FCPS',
        'reg' => '23264-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Abdul Hameed',
        'qualification' => 'MBBS, FCPS',
        'reg' => '25638-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Imtiaz Khan',
        'qualification' => 'MBBS, MCPS',
        'reg' => '12076-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Bilal',
        'qualification' => 'MBBS, FCPS',
        'reg' => '17291-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'ent' => [
    'name' => 'ENT',
    'icon' => 'bi-ear-fill',
    'group' => 'Clinical',
    'intro' => [
      'The ENT department covers disorders of the ear, nose, and throat with clinical and operative teaching for MBBS students.',
      'Students learn examination techniques, common ENT presentations, and appropriate referral pathways.',
    ],
    'hod' => 'Professor Dr. Shafi Ullah',
    'faculty' => [
      [
        'name' => 'Professor Dr. Shafi Ullah',
        'qualification' => 'MBBS, FCPS',
        'reg' => '4289-N',
      ],
      [
        'name' => 'Professor Dr. Mohammad Habib',
        'qualification' => 'MBBS, DLO, FCPS',
        'reg' => '4164-N',
      ],
      [
        'name' => 'Professor Dr. Arif Raza Khan',
        'qualification' => 'MBBS, FCPS',
        'reg' => '4026-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Farhan Salam',
        'qualification' => 'MBBS, FCPS',
        'reg' => '14626-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Naseem Ul Haq',
        'qualification' => 'MBBS, FCPS',
        'reg' => '4670-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'ophthalmology' => [
    'name' => 'Ophthalmology',
    'icon' => 'bi-eye-fill',
    'group' => 'Clinical',
    'intro' => [
      'The Department of Ophthalmology trains students in eye examination, common ocular diseases, and vision-related public health issues.',
      'Clinical teaching develops skills in recognition of sight-threatening conditions and timely referral.',
    ],
    'hod' => 'Professor Dr. Hafeez Ur Rahman',
    'faculty' => [
      [
        'name' => 'Professor Dr. Hafeez Ur Rahman',
        'qualification' => 'MBBS, FCPS',
        'reg' => '6392-P',
      ],
      [
        'name' => 'Professor Dr. Ayesha Sumera',
        'qualification' => 'MBBS, FCPS, Dip. (Ophthalmology), MHPE',
        'reg' => '6714-N',
      ],
      [
        'name' => 'Professor Dr. Mir Ali Shah',
        'qualification' => 'MBBS, MCPS, FCPS',
        'reg' => '2186-N',
      ],
      [
        'name' => 'Professor Dr. Ibrar Hussain',
        'qualification' => 'MBBS, MCPS, FCPS',
        'reg' => '3570-N',
      ],
      [
        'name' => 'Associate Professor Dr. Faisal Nawaz',
        'qualification' => 'MBBS, FCPS(Ophthalmology), FCPS(VR)',
        'reg' => '5663-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Abdul Munim',
        'qualification' => 'MBBS, FCPS (Ophthalmology), FCPS (VR)',
        'reg' => '16590-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Muhammad Usman',
        'qualification' => 'MBBS, FCPS (Ophthalmology), FCPS (VR)',
        'reg' => '70849-P',
      ],
      [
        'name' => 'Senior Registrar Dr. Muhammad Zaheer Ullah Baber',
        'qualification' => 'MBBS, FCPS (Ophthalmology), FCPS (VR)',
        'reg' => '27313-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'orthopaedics' => [
    'name' => 'Orthopaedics',
    'icon' => 'bi-bandaid-fill',
    'group' => 'Clinical',
    'intro' => [
      'Orthopaedics focuses on musculoskeletal injury and disease, trauma care, and rehabilitation principles.',
      'Students participate in clinics, wards, and trauma-related teaching at affiliated hospitals.',
    ],
    'hod' => 'Professor Dr. Mahmood Ul Hassan',
    'faculty' => [
      [
        'name' => 'Professor Dr. Mahmood Ul Hassan',
        'qualification' => 'MBBS, FCPS',
        'reg' => '7960-N',
      ],
      [
        'name' => 'Associate Professor Dr. Saeed Ahmad',
        'qualification' => 'MBBS, FCPS',
        'reg' => '15419-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Akhtar Hussain',
        'qualification' => 'MBBS, FCPS',
        'reg' => '15870-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Hidayat Ullah',
        'qualification' => 'MBBS, FCPS',
        'reg' => '14917-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'dermatology' => [
    'name' => 'Dermatology',
    'icon' => 'bi-droplet-half',
    'group' => 'Clinical',
    'intro' => [
      'The Department of Dermatology covers common skin diseases, clinical diagnosis, and outpatient-based teaching.',
      'Students learn pattern recognition and management approaches for frequently encountered dermatological conditions.',
    ],
    'hod' => 'Professor Dr. Miraj Muhammad Khan',
    'faculty' => [
      [
        'name' => 'Professor Dr. Miraj Muhammad Khan',
        'qualification' => 'MBBS, Diploma in Dermatology, FVMA',
        'reg' => '1921-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Hafsa Usman',
        'qualification' => 'MBBS, FCPS',
        'reg' => '18690-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Farah Sagheer',
        'qualification' => 'MBBS, FCPS',
        'reg' => '20164-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'radiology' => [
    'name' => 'Radiology',
    'icon' => 'bi-radioactive',
    'group' => 'Clinical',
    'intro' => [
      'Radiology introduces imaging modalities and interpretation skills essential for modern clinical practice.',
      'Teaching links radiological findings with clinical scenarios across medical and surgical specialties.',
    ],
    'hod' => 'Professor Dr. Abdul Majid',
    'faculty' => [
      [
        'name' => 'Professor Dr. Abdul Majid',
        'qualification' => 'MBBS, FCPS',
        'reg' => '44336-S',
      ],
      [
        'name' => 'Associate Professor Dr. Zeenat Adil',
        'qualification' => 'MBBS, D.M.R.D, FRCR',
        'reg' => '7576-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Abdul Aziz Zia',
        'qualification' => 'MBBS, D.M.R.D',
        'reg' => '968-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Ambreen Muhammad',
        'qualification' => 'MBBS, FCPS',
        'reg' => '13856-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Adnan Yousaf',
        'qualification' => 'MBBS, FCPS',
        'reg' => '11205-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Madiha Romasa Ilyas',
        'qualification' => 'MBBS, MCPS',
        'reg' => '7451-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Saima Rabbani',
        'qualification' => 'MBBS, FCPS',
        'reg' => '25104-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'anaesthesia' => [
    'name' => 'Anaesthesia',
    'icon' => 'bi-lungs-fill',
    'group' => 'Clinical',
    'intro' => [
      'The Department of Anaesthesia covers peri-operative care, airway management, pain control, and principles of safe anaesthesia.',
      'Students observe and learn critical skills relevant to emergency and operative medicine.',
    ],
    'hod' => 'Professor Dr. Rukhsana Samad',
    'faculty' => [
      [
        'name' => 'Professor Dr. Rukhsana Samad',
        'qualification' => 'MBBS, FCPS, MCPS',
        'reg' => '39-N',
      ],
      [
        'name' => 'Professor Dr. Neelam Noreen',
        'qualification' => 'MBBS, Diploma in Anaesthesia, FCPS',
        'reg' => '4089-N',
      ],
      [
        'name' => 'Associate Professor Dr. Adil Hussain',
        'qualification' => 'MBBS, Diploma in Anaesthesia',
        'reg' => '1913-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Ihsan Ul Haq',
        'qualification' => 'MBBS, Diploma in Anaesthesia',
        'reg' => '4634-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Muhammad Naeem Anjum',
        'qualification' => 'MBBS, Diploma in Anaesthesia',
        'reg' => '17430-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Abdul Rauf',
        'qualification' => 'MBBS, Diploma in Anaesthesia',
        'reg' => '22140-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'psychiatry' => [
    'name' => 'Psychiatry',
    'icon' => 'bi-hypnotize',
    'group' => 'Clinical',
    'intro' => [
      'Psychiatry training covers mental health assessment, common psychiatric disorders, and patient-centred biopsychosocial care.',
      'Students develop communication skills and an understanding of stigma-sensitive clinical practice.',
    ],
    'hod' => 'Professor Dr. Muhammad Irfan',
    'faculty' => [
      [
        'name' => 'Professor Dr. Muhammad Irfan',
        'qualification' => 'MBBS, MCPS, FCPS, Dip CBT, MS, PhD, CHPE',
        'reg' => '10719-N',
      ],
      [
        'name' => 'Professor Dr. Mohammad Idrees',
        'qualification' => 'MBBS, MCPS, FCPS, CHPE',
        'reg' => '875-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Asiya Munir',
        'qualification' => 'MBBS, FCPS (Psychiatry)',
        'reg' => '29963-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'cardiology' => [
    'name' => 'Cardiology',
    'icon' => 'bi-heart-fill',
    'group' => 'Clinical',
    'intro' => [
      'Cardiology training covers cardiovascular assessment, common heart diseases, and hospital-based clinical exposure.',
    ],
    'hod' => 'Professor Dr. Mohammad Saqib Qureshi',
    'faculty' => [
      [
        'name' => 'Professor Dr. Mohammad Saqib Qureshi',
        'qualification' => 'MBBS, FCPS',
        'reg' => '3398-N',
      ],
      [
        'name' => 'Associate Professor Dr. Muhammad Abdur Rauf',
        'qualification' => 'MBBS, FCPS',
        'reg' => '14299-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Rahid Ullah',
        'qualification' => 'MBBS, FCPS',
        'reg' => '18991-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Farhat Shireen',
        'qualification' => 'MBBS, FCPS',
        'reg' => '21393-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Naveed Danish',
        'qualification' => 'MBBS, FCPS',
        'reg' => '18353-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'cardiac-surgery' => [
    'name' => 'Cardiac Surgery',
    'icon' => 'bi-heart-pulse',
    'group' => 'Clinical',
    'intro' => [
      'Cardiac Surgery provides teaching related to surgical management of cardiac disease in tertiary-care settings.',
    ],
    'hod' => 'Assistant Professor Dr. Aamir Zeb',
    'faculty' => [
      [
        'name' => 'Assistant Professor Dr. Aamir Zeb',
        'qualification' => 'MBBS, FCPS',
        'reg' => '17510-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Muhammad Ishfaq',
        'qualification' => 'MBBS, FCPS (General Surgery)',
        'reg' => '19531-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Asif Ali',
        'qualification' => 'MBBS, FCPS (General Surgery)',
        'reg' => '26366-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Shumaila Farman',
        'qualification' => 'MBBS, FCPS (General Surgery)',
        'reg' => '27920-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'neurosurgery' => [
    'name' => 'Neurosurgery',
    'icon' => 'bi-radioactive',
    'group' => 'Clinical',
    'intro' => [
      'Neurosurgery provides exposure to disorders of the brain, spine, and peripheral nerves requiring surgical assessment and care.',
    ],
    'hod' => 'Associate Professor Dr. Akram Ullah',
    'faculty' => [
      [
        'name' => 'Associate Professor Dr. Akram Ullah',
        'qualification' => 'MBBS, FCPS',
        'reg' => '14422-N',
      ],
      [
        'name' => 'Associate Professor Dr. Muhammad Zubair',
        'qualification' => 'MBBS, FCPS',
        'reg' => '10946-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Sajid Khan',
        'qualification' => 'MBBS, FCPS',
        'reg' => '17017-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Arif Hussain',
        'qualification' => 'MBBS, FCPS',
        'reg' => '20338-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'neurology' => [
    'name' => 'Neurology',
    'icon' => 'bi-lightning-charge',
    'group' => 'Clinical',
    'intro' => [
      'Neurology focuses on disorders of the nervous system, clinical assessment, and neurological decision-making.',
    ],
    'hod' => 'Assistant Professor Dr. Bakht Jehan',
    'faculty' => [
      [
        'name' => 'Assistant Professor Dr. Bakht Jehan',
        'qualification' => 'MBBS, FCPS',
        'reg' => '5106-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'pulmonology' => [
    'name' => 'Pulmonology',
    'icon' => 'bi-lungs',
    'group' => 'Clinical',
    'intro' => [
      'Pulmonology covers respiratory diseases, clinical assessment, and hospital-based respiratory care teaching.',
    ],
    'hod' => 'Professor Dr. Fazli Maula',
    'faculty' => [
      [
        'name' => 'Professor Dr. Fazli Maula',
        'qualification' => 'MBBS, FCPS',
        'reg' => '4945-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Mehmood Khattak',
        'qualification' => 'MBBS, FCPS',
        'reg' => '13020-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Ahmed Usman',
        'qualification' => 'MBBS, FCPS',
        'reg' => '25599-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'gastroenterology' => [
    'name' => 'Gastroenterology',
    'icon' => 'bi-hospital',
    'group' => 'Clinical',
    'intro' => [
      'Gastroenterology focuses on diseases of the digestive system with clinical teaching in hospital settings.',
    ],
    'hod' => 'Assistant Professor Dr. Mian Shah Yousaf',
    'faculty' => [
      [
        'name' => 'Assistant Professor Dr. Mian Shah Yousaf',
        'qualification' => 'MBBS, FCPS',
        'reg' => '17803-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Arbab Muhammad Kashif Khan',
        'qualification' => 'MBBS, FCPS',
        'reg' => '17318-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Noman Khan',
        'qualification' => 'MBBS, FCPS',
        'reg' => '20380-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Yasir Abbas',
        'qualification' => 'MBBS, FCPS',
        'reg' => '14302-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'nephrology' => [
    'name' => 'Nephrology',
    'icon' => 'bi-droplet',
    'group' => 'Clinical',
    'intro' => [
      'Nephrology focuses on kidney disease, fluid and electrolyte disorders, and related clinical management.',
    ],
    'hod' => 'Associate Professor Dr. Najm UD Din',
    'faculty' => [
      [
        'name' => 'Associate Professor Dr. Najm UD Din',
        'qualification' => 'MBBS, FCPS',
        'reg' => '4104-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Shahid Rizwan Safir',
        'qualification' => 'MBBS, FCPS',
        'reg' => '7668-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'urology' => [
    'name' => 'Urology',
    'icon' => 'bi-clipboard2-pulse',
    'group' => 'Clinical',
    'intro' => [
      'Urology covers diseases of the urinary tract and male reproductive system with clinical and operative teaching.',
    ],
    'hod' => 'Professor Dr. Mir Alam Jan',
    'faculty' => [
      [
        'name' => 'Professor Dr. Mir Alam Jan',
        'qualification' => 'MBBS, FCPS',
        'reg' => '1919-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Muhammad Hisham Naeem',
        'qualification' => 'MBBS, FCPS',
        'reg' => '26983-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Izhar Ali',
        'qualification' => 'MBBS, FCPS',
        'reg' => '21826-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'endocrinology' => [
    'name' => 'Endocrinology',
    'icon' => 'bi-clipboard-pulse',
    'group' => 'Clinical',
    'intro' => [
      'Endocrinology focuses on hormonal disorders, diabetes care, and metabolic disease in clinical practice.',
    ],
    'hod' => 'Assistant Professor Dr. Kifayat Ali',
    'faculty' => [
      [
        'name' => 'Assistant Professor Dr. Kifayat Ali',
        'qualification' => 'MBBS, FCPS (Med), FCPS (Endo), MRCP (UK), CHPE, CHR',
        'reg' => '23931-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Sana',
        'qualification' => 'MBBS, FCPS (Med), FCPS (Endo), MRCP (UK), CHPE, CHR',
        'reg' => '23110-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'oncology' => [
    'name' => 'Oncology',
    'icon' => 'bi-virus',
    'group' => 'Clinical',
    'intro' => [
      'Oncology provides teaching related to cancer diagnosis, multidisciplinary care, and supportive management.',
    ],
    'hod' => 'Assistant Professor Dr. Bilal Ahmad',
    'faculty' => [
      [
        'name' => 'Assistant Professor Dr. Bilal Ahmad',
        'qualification' => 'MBBS, FCPS (Oncology)',
        'reg' => '22170-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Uzma Rahim',
        'qualification' => 'MBBS, FCPS (Clinical Haematology)',
        'reg' => '27012-N',
      ],
      [
        'name' => 'Senior Registrar Dr. Syeda Sama Bilal',
        'qualification' => 'MBBS, FCPS (Oncology)',
        'reg' => '20409-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'family-medicine' => [
    'name' => 'Family Medicine',
    'icon' => 'bi-house-heart',
    'group' => 'Clinical',
    'intro' => [
      'Family Medicine emphasizes comprehensive, continuity-based care across ages and common community presentations.',
    ],
    'hod' => 'Assistant Professor Dr. Humaira Khattak',
    'faculty' => [
      [
        'name' => 'Assistant Professor Dr. Humaira Khattak',
        'qualification' => 'MBBS, MCPS (Family Med.)',
        'reg' => '5930-N',
      ],
      [
        'name' => 'Assistant Professor Dr. Aminul Haq',
        'qualification' => 'MBBS, MCPS (Family Med.), FCPS (Medicine)',
        'reg' => '4120-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
  'critical-care' => [
    'name' => 'Critical Care',
    'icon' => 'bi-activity',
    'group' => 'Clinical',
    'intro' => [
      'Critical Care focuses on management of acutely ill patients and principles of intensive care medicine.',
    ],
    'hod' => 'Professor Dr. Zia Ullah',
    'faculty' => [
      [
        'name' => 'Professor Dr. Zia Ullah',
        'qualification' => 'MBBS, FCPS (Pulmonology)',
        'reg' => '3584-N',
      ],
      [
        'name' => 'Professor Dr. Fazli Wahab',
        'qualification' => 'MBBS, FCPS, (Med.), FCPS (Pulmonology)',
        'reg' => '7321-N',
      ],
    ],
    'activities' => [
      [
        'title' => 'Ward & Clinic Teaching',
        'date' => 'Ongoing 2026',
        'text' => 'Bedside teaching, outpatient exposure, and case-based discussions during clinical rotations.',
      ],
      [
        'title' => 'Case Presentations',
        'date' => 'Weekly',
        'text' => 'Student case presentations with consultant feedback to strengthen clinical reasoning.',
      ],
      [
        'title' => 'CME / Departmental Meetings',
        'date' => '2025–26',
        'text' => 'Continuing medical education sessions and departmental academic meetings.',
      ],
    ],
  ],
];

/** Default last-updated when a department has no explicit `updated` value (Y-m-d). */
const DEPARTMENTS_DEFAULT_UPDATED = '2026-08-09';

foreach ($academic_departments as $slug => &$dept) {
  if (empty($dept['updated'])) {
    $dept['updated'] = DEPARTMENTS_DEFAULT_UPDATED;
  }
}
unset($dept);

function get_academic_department(string $slug): ?array {
  global $academic_departments;
  return $academic_departments[$slug] ?? null;
}

function get_department_activity(string $slug, int $index): ?array {
  $dept = get_academic_department($slug);
  if (!$dept) {
    return null;
  }
  $activities = $dept['activities'] ?? [];
  if (!isset($activities[$index]) || !is_array($activities[$index])) {
    return null;
  }
  return [
    'dept' => $dept,
    'slug' => $slug,
    'index' => $index,
    'activity' => $activities[$index],
  ];
}

function academic_department_groups(array $departments): array {
  $groups = [];
  foreach ($departments as $slug => $dept) {
    $group = $dept['group'] ?? 'Other';
    $groups[$group][$slug] = $dept;
  }
  return $groups;
}

function department_updated_label(array $dept): string {
  $raw = $dept['updated'] ?? DEPARTMENTS_DEFAULT_UPDATED;
  $ts = strtotime($raw);
  return $ts ? date('j M Y', $ts) : (string) $raw;
}

/** ORIC department IDs from https://oric.prime.edu.pk/publications.php */
function department_oric_id(string $slug): ?int {
  $map = [
    'anatomy' => 4,
    'physiology' => 5,
    'biochemistry' => 3,
    'pathology' => 8,
    'pharmacology' => 5010,
    'forensic-medicine' => 5011,
    'chs' => 5007,
    'dhpe' => 11,
    'medicine' => 1,
    'surgery' => 5001,
    'gynaecology' => 5003,
    'paediatrics' => 5002,
    'ent' => 5020,
    'ophthalmology' => 5030,
    'orthopaedics' => 5027,
    'radiology' => 5004,
    'psychiatry' => 9,
    'cardiology' => 5035,
  ];
  return $map[$slug] ?? null;
}

function department_oric_publications_url(string $slug): string {
  $id = department_oric_id($slug);
  if ($id === null) {
    return 'https://oric.prime.edu.pk/publications.php';
  }
  return 'https://oric.prime.edu.pk/dep_research.php?id=' . $id;
}
