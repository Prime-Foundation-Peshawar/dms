-- Restore names removed by the CMS seed importer; preserve populated slots and other page edits.
UPDATE pages SET body_html = REPLACE(body_html, '<strong></strong>', '<strong><span>Prof. Dr. Mohsina Haq</span></strong>') WHERE slug = 'pg-medical-education';
UPDATE pages SET body_html = REPLACE(body_html, 'Teaching staff of Basic Medical Sciences, under .', 'Teaching staff of Basic Medical Sciences, under <span>Prof. Dr. Mohsina Haq</span>.') WHERE slug = 'pg-medical-education';
UPDATE pages SET body_html = REPLACE(body_html, '<h4>Anatomy</h4>
                <ul class="pg-staff-list">
                  <li></li>
                  <li></li>
                </ul>', '<h4>Anatomy</h4>
                <ul class="pg-staff-list">
                  <li><span>Dr. Nouman Ullah Wazir</span><span class="pg-qual">PhD</span></li>
                  <li><span>Dr. Farooq Khan</span><span class="pg-qual">PhD</span></li>
                </ul>') WHERE slug = 'pg-medical-education';
UPDATE pages SET body_html = REPLACE(body_html, '<h4>Anatomy</h4>
                <ul class="pg-staff-list">
                  <li></li>
                  <li></li>
                </ul>', '<h4>Anatomy</h4>
                <ul class="pg-staff-list">
                  <li><span>Dr. Nouman Ullah Wazir</span><span class="pg-qual">PhD</span></li>
                  <li><span>Dr. Farooq Khan</span><span class="pg-qual">PhD</span></li>
                </ul>') WHERE slug = 'pg-medical-education';
UPDATE pages SET body_html = REPLACE(body_html, '<h4>Physiology</h4>
                <ul class="pg-staff-list">
                  <li></li>
                  <li></li>
                  <li></li>
                </ul>', '<h4>Physiology</h4>
                <ul class="pg-staff-list">
                  <li><span>Dr. Robina Riaz</span></li>
                  <li><span>Dr. Munaza Khattak</span></li>
                  <li><span>Dr. Farzana Khan</span></li>
                </ul>') WHERE slug = 'pg-medical-education';
UPDATE pages SET body_html = REPLACE(body_html, '<h4>Physiology</h4>
                <ul class="pg-staff-list">
                  <li></li>
                  <li></li>
                  <li></li>
                </ul>', '<h4>Physiology</h4>
                <ul class="pg-staff-list">
                  <li><span>Dr. Robina Riaz</span></li>
                  <li><span>Dr. Munaza Khattak</span></li>
                  <li><span>Dr. Farzana Khan</span></li>
                </ul>') WHERE slug = 'pg-medical-education';
UPDATE pages SET body_html = REPLACE(body_html, '<h4>Pharmacology</h4>
                <ul class="pg-staff-list">
                  <li></li>
                  <li></li>
                </ul>', '<h4>Pharmacology</h4>
                <ul class="pg-staff-list">
                  <li><span>Dr. Shafaq Zafar</span><span class="pg-qual">PhD</span></li>
                  <li><span>Dr. Amber Javaid</span><span class="pg-qual">MPhil</span></li>
                </ul>') WHERE slug = 'pg-medical-education';
UPDATE pages SET body_html = REPLACE(body_html, '<h4>Pharmacology</h4>
                <ul class="pg-staff-list">
                  <li></li>
                  <li></li>
                </ul>', '<h4>Pharmacology</h4>
                <ul class="pg-staff-list">
                  <li><span>Dr. Shafaq Zafar</span><span class="pg-qual">PhD</span></li>
                  <li><span>Dr. Amber Javaid</span><span class="pg-qual">MPhil</span></li>
                </ul>') WHERE slug = 'pg-medical-education';
UPDATE pages SET body_html = REPLACE(body_html, '<h4>Pathology</h4>
                <ul class="pg-staff-list">
                  <li></li>
                  <li></li>
                  <li></li>
                </ul>', '<h4>Pathology</h4>
                <ul class="pg-staff-list">
                  <li><span>Dr. Mohsina Haq</span><span class="pg-qual">PhD</span></li>
                  <li><span>Dr. Wasifa Noshad</span><span class="pg-qual">PhD</span></li>
                  <li><span>Dr. Hala Rajab</span><span class="pg-qual">PhD</span></li>
                </ul>') WHERE slug = 'pg-medical-education';
UPDATE pages SET body_html = REPLACE(body_html, '<h4>Pathology</h4>
                <ul class="pg-staff-list">
                  <li></li>
                  <li></li>
                  <li></li>
                </ul>', '<h4>Pathology</h4>
                <ul class="pg-staff-list">
                  <li><span>Dr. Mohsina Haq</span><span class="pg-qual">PhD</span></li>
                  <li><span>Dr. Wasifa Noshad</span><span class="pg-qual">PhD</span></li>
                  <li><span>Dr. Hala Rajab</span><span class="pg-qual">PhD</span></li>
                </ul>') WHERE slug = 'pg-medical-education';
UPDATE pages SET body_html = REPLACE(body_html, '<h4>Biochemistry</h4>
                <ul class="pg-staff-list">
                  <li></li>
                  <li></li>
                </ul>', '<h4>Biochemistry</h4>
                <ul class="pg-staff-list">
                  <li><span>Dr. Shumaila Asim</span><span class="pg-qual">PhD</span></li>
                  <li><span>Dr. Zahoor Ullah</span><span class="pg-qual">PhD</span></li>
                </ul>') WHERE slug = 'pg-medical-education';
UPDATE pages SET body_html = REPLACE(body_html, '<h4>Biochemistry</h4>
                <ul class="pg-staff-list">
                  <li></li>
                  <li></li>
                </ul>', '<h4>Biochemistry</h4>
                <ul class="pg-staff-list">
                  <li><span>Dr. Shumaila Asim</span><span class="pg-qual">PhD</span></li>
                  <li><span>Dr. Zahoor Ullah</span><span class="pg-qual">PhD</span></li>
                </ul>') WHERE slug = 'pg-medical-education';
