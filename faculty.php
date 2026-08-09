<?php include('includes/header.php'); ?>

<style>
  /* Clean table styling for faculty list */
  .dept-members table {
    width: 100%;
    border-collapse: collapse;
    font-family: var(--font-body);
  }
  .dept-members th {
    text-align: left;
    padding: 10px 12px;
    font-family: var(--font-head);
    font-size: .72rem;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #64748b;
    border-bottom: 2px solid #e2e8f0;
  }
  .dept-members td {
    padding: 10px 12px;
    border-bottom: 1px solid #f1f5f9;
    font-size: .85rem;
    color: #334155;
  }
  .dept-members tr:last-child td {
    border-bottom: none;
  }
  .reg-number {
    font-family: 'SF Mono', 'Fira Code', monospace;
    font-size: 0.82rem;
    color: #475569;
    background: #f8fafc;
    padding: 2px 10px;
    border-radius: 4px;
  }
</style>

<link href="assets/css/faculty.css" rel="stylesheet"/>

<!-- PAGE HERO -->
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Our Distinguished Faculty</h1>
    <div class="breadcrumb-pmc">
      <a href="index.php">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="about.php">About Us</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Faculty</span>
    </div>
  </div>
</div>

<!-- MAIN -->
<section class="pmc-section bg-off">
  <div class="container">

    <!-- INTRO -->
    <div class="row mb-5 fu">
      <div class="col-lg-8">
        <span class="sec-eyebrow">About Our Faculty</span>
        <h2 class="sec-title">Expert Clinicians & Academic Professionals</h2>
        <p class="sec-desc">Riphah International University - Peshawar Campus is proud to have a highly qualified, experienced, and dedicated team of professors, associate professors, assistant professors, sr. registrars, jr. registrars, sr. lecturers, and lecturers — all PM&DC registered — spanning every department of the MBBS curriculum.</p>
      </div>
    </div>

    <div class='table-responsive'>
     <table class='table table-bordered table-striped table-hover'>
        <!--<thead>-->
        <!--    <tr>-->
        <!--        <th>Faculty Department of Medical Sciences - Riphah International Campus Peshawar </th>-->
        <!--        <th></th>-->
        <!--        <th></th>-->
        <!--        <th></th>-->
        <!--    </tr>-->
        <!--</thead>-->
        <tbody>
            <!--<tr>-->
            <!--    <td></td>-->
            <!--    <td></td>-->
            <!--    <td></td>-->
            <!--    <td></td>-->
            <!--</tr>-->
            <tr class="bg-success text-light">
                <th class="bg-success text-light">Physiology</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Robina Riaz</td>
                <td>MBBS, M.Phil, MHPE, PhD </td>
                <td>3929-N</td>
                <td>8483/3929-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Farzana Salman</td>
                <td>MBBS, M.Phil, PhD </td>
                <td>4576-N</td>
                <td>2376/4576-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Momina Haq</td>
                <td>MBBS, M.Phil</td>
                <td>20339-N</td>
                <td>17256/20339-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Fatima Saadat</td>
                <td>BDS, M.Phil</td>
                <td>21384-D</td>
                <td>163547/21384-D/D</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Anatomy</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Jehanzeb Khan</td>
                <td>MBBS, M.Phil</td>
                <td>1780-N</td>
                <td>11147/1780-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Ihsan Ullah Wazir</td>
                <td>MBBS, M.Phil</td>
                <td>1887-N</td>
                <td>1887-N/11151/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Mohammad Saeed</td>
                <td>MBBS, M.Phil</td>
                <td>2131-N</td>
                <td>2237/2131-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Muhammad Imran Qureshi</td>
                <td>MBBS, M.Phil</td>
                <td>2205-N</td>
                <td>2268/2205-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Noman Ullah Wazir</td>
                <td>MBBS, M.Phil, PhD </td>
                <td>17344-N</td>
                <td>11962/17344-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Mariya Azam Khattak</td>
                <td>MBBS, M.Phil</td>
                <td>19170-D</td>
                <td>89896/19170-D/D</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Biochemistry</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Sadia Haroon</td>
                <td>MBBS, M.Phil</td>
                <td>13072-P</td>
                <td>2226/13072-P/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Shamaila Wadud</td>
                <td>MBBS, M.Phil, PhD </td>
                <td>9330-N</td>
                <td>4899/9330-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Sara Yar Khan</td>
                <td>BDS, M.Phil</td>
                <td>22465-D</td>
                <td>341541/22465-D/D</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Sikandar Ali Khan</td>
                <td>MBBS, M.Phil</td>
                <td>23145-N</td>
                <td>89484/23145-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Pathology</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Fozia Rauf</td>
                <td>MBBS, FCPS (Histopathology)</td>
                <td>9167-N</td>
                <td>1620/9167-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Mohsina Haq</td>
                <td>MBBS, M.Phil (Microbiology), PhD</td>
                <td>17218-N</td>
                <td>11218/17218-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Ambreen Gul</td>
                <td>MBBS, M.Phil (Chemical Pathology)</td>
                <td>11850-N</td>
                <td>2372/11850-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Mian Ihsanullah</td>
                <td>MBBS, M.Phil (Chemical Pathology), DCP</td>
                <td>475-N</td>
                <td>10321/475-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Ashraf Khan</td>
                <td>MBBS, M.Phil (Haematology)</td>
                <td>2445-N</td>
                <td>28813/2445-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Shabina Saifullah</td>
                <td>MBBS, M.Phil (Chemical Pathology)</td>
                <td>8611-N</td>
                <td>26548/8611-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Sumira Abbas</td>
                <td>MBBS, M.Phil (Haematology)</td>
                <td>21012-N</td>
                <td>168806/21012-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Sara Yousaf</td>
                <td>MBBS, M.Phil (Histopathology)</td>
                <td>80451-N</td>
                <td>28834/80451-P/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Ashfaq Ahmad</td>
                <td>MBBS, M.Phil (Microbiology)</td>
                <td>25375-N</td>
                <td>34973/25375-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Haseeba Arif</td>
                <td>BDS, M.Phil (Microbiology)</td>
                <td>18937-D</td>
                <td>125819/18937-D/D</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Pharmacology</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Shafaq Zafar</td>
                <td>MBBS, M.Phil, PhD</td>
                <td>14188-N</td>
                <td>4889/14188-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Riaz Nasim</td>
                <td>MBBS, M.Phil</td>
                <td>598-N</td>
                <td>11060/598-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Amber Javed</td>
                <td>MBBS, M.Phil</td>
                <td>10512-N</td>
                <td>11777/10512-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Erum Rehman</td>
                <td>BDS, M.Phil</td>
                <td>18609-D</td>
                <td>29773/18609-D/D</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Muhammad Shahid</td>
                <td>MBBS, M.Phil</td>
                <td>28805-N</td>
                <td>737228/28805-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Forensic Medicine</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Rubina Salma Yasmin</td>
                <td>MBBS, MCPS, M.Phil </td>
                <td>814-N</td>
                <td>11168/814-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Muhammad Bilal Khan</td>
                <td>MBBS, DMJ</td>
                <td>28264-N</td>
                <td>32768/28264-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Muhammad Wasif</td>
                <td>MBBS, M.Phil</td>
                <td>30470-N</td>
                <td>30470-N</td>
            </tr>
            <tr>
                <th class="bg-success text-light">CHS</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Mohammad Aman Khan</td>
                <td>MBBS, M.Phil, MCPS, DOMS, DCEH</td>
                <td>1706-N</td>
                <td>3743/1706-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Saeed Anwar</td>
                <td>MBBS, MPH, ADHPE</td>
                <td>1960-N</td>
                <td>1618/1960-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Farhat R Malik</td>
                <td>MBBS, MPH</td>
                <td>7155-N</td>
                <td>11358/7155-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Faqir Muhammad Anwar</td>
                <td>MD, MPH</td>
                <td>1282-N</td>
                <td>1616/1282-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Aziza Alam</td>
                <td>MBBS, M.Phil </td>
                <td>7401-N</td>
                <td>7401-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">DHPE &amp; R</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Neelofar Shaheen</td>
                <td>MBBS, MHPE</td>
                <td>13134-N</td>
                <td>20758/13134-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Urooj Saleem</td>
                <td>BDS, MHPE</td>
                <td>10935-D</td>
                <td>12694 /10935-D/D</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Uzma Siddique</td>
                <td>BDS, MHPE</td>
                <td>9198-D</td>
                <td>7100/9198-D/D</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Psychiatry</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Muhammad Irfan</td>
                <td>MBBS, MCPS, FCPS, MS, PhD</td>
                <td>10719-N</td>
                <td>16192/10719-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Mohammad Idrees</td>
                <td>MBBS, FCPS </td>
                <td>875-N</td>
                <td>4068/875-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Asiya Munir</td>
                <td>MBBS, FCPS</td>
                <td>29963-N</td>
                <td>841935/29963-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Medicine</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Muhammad Subhan</td>
                <td>MBBS, FCPS</td>
                <td>2542-N</td>
                <td>1608/2542-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Najib Ul Haq</td>
                <td>MBBS, MCPS, MRCP (UK)</td>
                <td>1795-N</td>
                <td>2229/1795-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Jehanzeb Afridi</td>
                <td>MBBS, FCPS</td>
                <td>5101-N</td>
                <td>1607/5101-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Faridullah Shah</td>
                <td>MBBS, FCPS</td>
                <td>3190-N</td>
                <td>1609/3190-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Noor Mohammad</td>
                <td>MBBS, FCPS</td>
                <td>4053-N</td>
                <td>7747/4053-N/M</td>
            </tr>
            <tr>
                <td>Associate Professor Dr. Bakht Biland Khan</td>
                <td>MBBS, FCPS</td>
                <td>6180-N</td>
                <td>1619/6180-N/M</td>
            </tr>
            <tr>
                <td>Associate Professor Dr. Muhammad Abbas</td>
                <td>MBBS, FCPS</td>
                <td>14841-N</td>
                <td>23955/14841-N/M</td>
            </tr>
            <tr>
                <td>Associate Professor Dr. Saima Mehboob</td>
                <td>MBBS, FCPS</td>
                <td>13297-N</td>
                <td>18634/13297-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Muhammad Qasim</td>
                <td>MBBS, FCPS</td>
                <td>17354-N</td>
                <td>224555/17354-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Abdul Samad</td>
                <td>MBBS, Dip. Card</td>
                <td>852-N</td>
                <td>16661/852-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Nafees Ahmad</td>
                <td>MBBS, FCPS</td>
                <td>14584-N</td>
                <td>703570/14584-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Yasir Iqbal</td>
                <td>MBBS, FCPS</td>
                <td>29023-N</td>
                <td>747303/29023-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Surgery</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Mohammad Tayeb</td>
                <td>MBBS, FCPS</td>
                <td>9016-N</td>
                <td>1612/9016-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Ijaz Ahmad</td>
                <td>MBBS, FCPS</td>
                <td>2237-N</td>
                <td>8075/2237-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Muzaffur Ud Din Sadiq</td>
                <td>MBBS, FCPS</td>
                <td>1831-N</td>
                <td>33749/1831-N/M</td>
            </tr>
            <tr>
                <td>Associate Professor Dr. Sahibzada Salma Rahman</td>
                <td>MBBS, FCPS</td>
                <td>10023-N</td>
                <td>7748/10023-N/M</td>
            </tr>
            <tr>
                <td>Associate Professor Dr. Sheikh Muhammad Ibqar Azeem</td>
                <td>MBBS, FCPS</td>
                <td>13791-N</td>
                <td>21591/13791-N/M</td>
            </tr>
            <tr>
                <td>Associate Professor Dr. Ahmad Arsalan Tahir</td>
                <td>MBBS, FCPS, FRCS (Edinburgh)</td>
                <td>17595-N</td>
                <td>34933/17595-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Shahid Ullah Ahmad</td>
                <td>MBBS, FCPS</td>
                <td>15170-N</td>
                <td>29455/15170-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Waleed Mabood</td>
                <td>MBBS, FCPS</td>
                <td>23916-N</td>
                <td>23471/23916-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Ambareen Subhan</td>
                <td>MBBS, FCPS</td>
                <td>20087-N</td>
                <td>68123/20087-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Shoaib Muhammad</td>
                <td>MBBS, FCPS</td>
                <td>19009-N</td>
                <td>699884/19009-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Asad Ullah Khan</td>
                <td>MBBS, FCPS</td>
                <td>18341-N</td>
                <td>191743/18341-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Ophthalmology</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Hafeez Ur Rahman</td>
                <td>MBBS, FCPS</td>
                <td>6392-P</td>
                <td>11775/6392-P/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Ayesha Sumera</td>
                <td>MBBS, FCPS, Dip. (Ophthalmology), MHPE</td>
                <td>6714-N</td>
                <td>1592/6714-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Mir Ali Shah</td>
                <td>MBBS, MCPS, FCPS</td>
                <td>2186-N</td>
                <td>30279/2186-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Ibrar Hussain</td>
                <td>MBBS, MCPS, FCPS</td>
                <td>3570-N</td>
                <td>11481/3570-N/M</td>
            </tr>
            <tr>
                <td>Associate Professor Dr. Faisal Nawaz</td>
                <td>MBBS, FCPS(Ophthalmology), FCPS(VR)</td>
                <td>5663-N</td>
                <td>15419/5663-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Abdul Munim</td>
                <td>MBBS, FCPS (Ophthalmology), FCPS (VR)</td>
                <td>16590-N</td>
                <td>35002/16590-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Muhammad Usman</td>
                <td>MBBS, FCPS (Ophthalmology), FCPS (VR)</td>
                <td>70849-P</td>
                <td>157021/70849-P/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Muhammad Zaheer Ullah Baber</td>
                <td>MBBS, FCPS (Ophthalmology), FCPS (VR)</td>
                <td>27313-N</td>
                <td>709101/27313-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Gynae and Obstetrics</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Tehniyat Attiya Ur Razaq</td>
                <td>MBBS, FCPS</td>
                <td>6459-N</td>
                <td>1593/6459-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Shahnaz Parveen</td>
                <td>MBBS, MCPS, FCPS</td>
                <td>1871-N</td>
                <td>2265/1871-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Samdana Wahab</td>
                <td>MBBS, FCPS</td>
                <td>7886-N</td>
                <td>100041/7886-N/M</td>
            </tr>
            <tr>
                <td>Associate Professor Dr. Shamim Akhtar</td>
                <td>MBBS, FCPS</td>
                <td>43247-P</td>
                <td>23954/43247-P/M</td>
            </tr>
            <tr>
                <td>Associate Professor Dr. Shandana Bawar</td>
                <td>MBBS, FCPS, MRCOG</td>
                <td>9071-N</td>
                <td>16214/9071-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Farzana Burki</td>
                <td>MBBS, FCPS</td>
                <td>14305-N</td>
                <td>36218/14305-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Robina Qadeer</td>
                <td>MBBS, FCPS</td>
                <td>13006-N</td>
                <td>14599/13006-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Afrah Aman</td>
                <td>MBBS, FCPS</td>
                <td>17887-N</td>
                <td>36057/17887-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Sobia Siraj</td>
                <td>MBBS, FCPS</td>
                <td>19166-N</td>
                <td>358757/19166-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Asma Ghani</td>
                <td>MBBS, FCPS</td>
                <td>18364-N</td>
                <td>16720/18364-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Sana Nafees</td>
                <td>MBBS, FCPS</td>
                <td>21041-N</td>
                <td>21041-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Radiology</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Abdul Majid</td>
                <td>MBBS, FCPS</td>
                <td>44336-S</td>
                <td>2359/44336-S/M</td>
            </tr>
            <tr>
                <td>Associate Professor Dr. Zeenat Adil</td>
                <td>MBBS, D.M.R.D, FRCR</td>
                <td>7576-N</td>
                <td>29777/7576-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Abdul Aziz Zia</td>
                <td>MBBS, D.M.R.D</td>
                <td>968-N</td>
                <td>9585/968-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Ambreen Muhammad</td>
                <td>MBBS, FCPS</td>
                <td>13856-N</td>
                <td>13856-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Adnan Yousaf</td>
                <td>MBBS, FCPS</td>
                <td>11205-N</td>
                <td>11205-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Madiha Romasa Ilyas</td>
                <td>MBBS, MCPS</td>
                <td>7451-N</td>
                <td>11350/7451-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Saima Rabbani</td>
                <td>MBBS, FCPS</td>
                <td>25104-N</td>
                <td>649136/25104-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Orthopedics</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Mahmood Ul Hassan</td>
                <td>MBBS, FCPS</td>
                <td>7960-N</td>
                <td>4884/7960-N/M</td>
            </tr>
            <tr>
                <td>Associate Professor Dr. Saeed Ahmad</td>
                <td>MBBS, FCPS</td>
                <td>15419-N</td>
                <td>30028/15419-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Akhtar Hussain</td>
                <td>MBBS, FCPS</td>
                <td>15870-N</td>
                <td>38093/15870-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Hidayat Ullah</td>
                <td>MBBS, FCPS</td>
                <td>14917-N</td>
                <td>223966/14917-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">ENT</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Shafi Ullah</td>
                <td>MBBS, FCPS</td>
                <td>4289-N</td>
                <td>1585/4289-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Mohammad Habib</td>
                <td>MBBS, DLO, FCPS</td>
                <td>4164-N</td>
                <td>3459/4164-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Arif Raza Khan</td>
                <td>MBBS, FCPS</td>
                <td>4026-N</td>
                <td>4026/4026-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Farhan Salam</td>
                <td>MBBS, FCPS</td>
                <td>14626-N</td>
                <td>20589/14626-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Naseem Ul Haq</td>
                <td>MBBS, FCPS</td>
                <td>4670-N</td>
                <td>38070/4670-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Dermatology</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Miraj Muhammad Khan</td>
                <td>MBBS, Diploma in Dermatology, FVMA</td>
                <td>1921-N</td>
                <td>16308/1921-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Hafsa Usman</td>
                <td>MBBS, FCPS</td>
                <td>18690-N</td>
                <td>224944/18690-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Farah Sagheer</td>
                <td>MBBS, FCPS</td>
                <td>20164-N</td>
                <td>841943/20164-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Paediatrics</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Saima Ali</td>
                <td>MBBS, FCPS</td>
                <td>7435-N</td>
                <td>1598/7435-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Shazia Aurangzeb</td>
                <td>MBBS, FCPS</td>
                <td>8628-N</td>
                <td>1599/8628-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Saffiullah</td>
                <td>MBBS, MRCPCH (UK), MRCPS (Glasgow)</td>
                <td>9141-N</td>
                <td>10246/9141-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Hameed Ullah</td>
                <td>MBBS, MRCPCH (UK) </td>
                <td>9601-N</td>
                <td>2146/9601-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Rabbia Shaheen</td>
                <td>MBBS, FCPS</td>
                <td>19131-N</td>
                <td>159829/19131-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Sana Nafis</td>
                <td>MBBS, FCPS</td>
                <td>23264-N</td>
                <td>233201/23264-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Abdul Hameed</td>
                <td>MBBS, FCPS</td>
                <td>25638-N</td>
                <td>596375/25638-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Imtiaz Khan</td>
                <td>MBBS, MCPS</td>
                <td>12076-N</td>
                <td>12076-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Bilal</td>
                <td>MBBS, FCPS</td>
                <td>17291-N</td>
                <td>738877/17291-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Anaesthesia</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Rukhsana Samad</td>
                <td>MBBS, FCPS, MCPS</td>
                <td>39-N</td>
                <td>16459/39-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Neelam Noreen</td>
                <td>MBBS, Diploma in Anaesthesia, FCPS</td>
                <td>4089-N</td>
                <td>24012/4089-N/M</td>
            </tr>
            <tr>
                <td>Associate Professor Dr. Adil Hussain</td>
                <td>MBBS, Diploma in Anaesthesia</td>
                <td>1913-N</td>
                <td>1588/1913-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Ihsan Ul Haq</td>
                <td>MBBS, Diploma in Anaesthesia</td>
                <td>4634-N</td>
                <td>4634-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Muhammad Naeem Anjum</td>
                <td>MBBS, Diploma in Anaesthesia</td>
                <td>17430-N</td>
                <td>17430-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Abdul Rauf</td>
                <td>MBBS, Diploma in Anaesthesia</td>
                <td>22140-N</td>
                <td>22140-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Neurosurgery</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Associate Professor Dr. Akram Ullah</td>
                <td>MBBS, FCPS</td>
                <td>14422-N</td>
                <td>36726/14422-N/M</td>
            </tr>
            <tr>
                <td>Associate Professor Dr. Muhammad Zubair</td>
                <td>MBBS, FCPS</td>
                <td>10946-N</td>
                <td>27883/10946-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Sajid Khan</td>
                <td>MBBS, FCPS</td>
                <td>17017-N</td>
                <td>33907/17017-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Arif Hussain</td>
                <td>MBBS, FCPS</td>
                <td>20338-N</td>
                <td>38290/20338-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Gastroenterology</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Mian Shah Yousaf</td>
                <td>MBBS, FCPS</td>
                <td>17803-N</td>
                <td>36782/17803-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Arbab Muhammad Kashif Khan</td>
                <td>MBBS, FCPS</td>
                <td>17318-N</td>
                <td>37759/17318-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Noman Khan</td>
                <td>MBBS, FCPS</td>
                <td>20380-N</td>
                <td>17393/20380-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Yasir Abbas</td>
                <td>MBBS, FCPS</td>
                <td>14302-N</td>
                <td>201115/14302-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Cardiology</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Mohammad Saqib Qureshi</td>
                <td>MBBS, FCPS</td>
                <td>3398-N</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Associate Professor Dr. Muhammad Abdur Rauf</td>
                <td>MBBS, FCPS</td>
                <td>14299-N</td>
                <td>36416/14299-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Rahid Ullah</td>
                <td>MBBS, FCPS</td>
                <td>18991-N</td>
                <td>708543/18991-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Farhat Shireen</td>
                <td>MBBS, FCPS</td>
                <td>21393-N</td>
                <td>759214/21393-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Naveed Danish</td>
                <td>MBBS, FCPS</td>
                <td>18353-N</td>
                <td>841926/18353-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Cardiac Surgery</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Aamir Zeb</td>
                <td>MBBS, FCPS</td>
                <td>17510-N</td>
                <td>747847/17510-N/M</td>
            </tr>
            <tr>
                <td>Accident and Emergency</td>
                <td></td>
                <td>PM&DC Reg No.</td>
                <td>Faculty Reg No.</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Muhammad Ishfaq</td>
                <td>MBBS, FCPS (General Surgery)</td>
                <td>19531-N</td>
                <td>68035/19531-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Asif Ali</td>
                <td>MBBS, FCPS (General Surgery)</td>
                <td>26366-N</td>
                <td>26366-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Shumaila Farman</td>
                <td>MBBS, FCPS (General Surgery)</td>
                <td>27920-N</td>
                <td>27920-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Neurology</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Bakht Jehan</td>
                <td>MBBS, FCPS</td>
                <td>5106-N</td>
                <td>33954/5106-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Pulmonology</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Fazli Maula</td>
                <td>MBBS, FCPS</td>
                <td>4945-N</td>
                <td>3751/4945-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Mehmood Khattak</td>
                <td>MBBS, FCPS</td>
                <td>13020-N</td>
                <td>737754/13020-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Ahmed Usman</td>
                <td>MBBS, FCPS</td>
                <td>25599-N</td>
                <td>708459/25599-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Nephrology</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Associate Professor Dr. Najm UD Din</td>
                <td>MBBS, FCPS</td>
                <td>4104-N</td>
                <td>36981/4104-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Shahid Rizwan Safir</td>
                <td>MBBS, FCPS</td>
                <td>7668-N</td>
                <td>229562/7668-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Urology</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Mir Alam Jan</td>
                <td>MBBS, FCPS</td>
                <td>1919-N</td>
                <td>25043/1919-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Muhammad Hisham Naeem</td>
                <td>MBBS, FCPS</td>
                <td>26983-N</td>
                <td>841942/26983-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Izhar Ali</td>
                <td>MBBS, FCPS</td>
                <td>21826-N</td>
                <td>21826-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Endocrinology</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Kifayat Ali</td>
                <td>MBBS, FCPS (Med), MRCP (Med), FCPS (Endo)</td>
                <td>23931-N</td>
                <td>745550/23931-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Sana</td>
                <td>MBBS, FCPS (Med), MRCP (Med), FCPS (Endo)</td>
                <td>23110-N</td>
                <td>23110-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Oncology</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Bilal Ahmad</td>
                <td>MBBS, FCPS (Oncology)</td>
                <td>22170-N</td>
                <td>233614/22170-N/M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Uzma Rahim</td>
                <td>MBBS, FCPS (Clinical Haematology)</td>
                <td>27012-N</td>
                <td>738934/27012/N-M</td>
            </tr>
            <tr>
                <td>Senior Registrar Dr. Syeda Sama Bilal</td>
                <td>MBBS, FCPS (Oncology)</td>
                <td>20409-N</td>
                <td>841980/20409-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Family Medicine</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Humaira Khattak</td>
                <td>MBBS, MCPS (Family Med.)</td>
                <td>5930-N</td>
                <td>90081/5930-N/M</td>
            </tr>
            <tr>
                <td>Assistant Professor Dr. Aminul Haq</td>
                <td>MBBS, MCPS (Family Med.), FCPS (Medicine)</td>
                <td>4120-N</td>
                <td>2235/4120-N/M</td>
            </tr>
            <tr>
                <th class="bg-success text-light">Critical Care</th>
                <th class="bg-success text-light">Qualification</th>
                <th class="bg-success text-light">PM&DC Reg No.</th>
                <th class="bg-success text-light">Faculty Reg No.</th>
            </tr>
            <tr>
                <td>Professor Dr. Zia Ullah</td>
                <td>MBBS, FCPS (Pulmonology)</td>
                <td>3584-N</td>
                <td>189255/3584-N/M</td>
            </tr>
            <tr>
                <td>Professor Dr. Fazli Wahab</td>
                <td>MBBS, FCPS, (Med.), FCPS (Pulmonology)</td>
                <td>7321-N</td>
                <td>1610/7321-N/M</td>
            </tr>
        </tbody>
    </table>
</div>

  </div>
</section>

<?php include('includes/footer.php'); ?>