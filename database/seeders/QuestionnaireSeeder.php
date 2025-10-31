<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\Section;
use Illuminate\Database\Seeder;

class QuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $questionnaires = [
            [
                'title' => 'Business Model Assessment',
                'section' => 'business_model',
                'description' => 'Understand your business model, services, and operational structure',
                'questions' => [
                    [
                        'question' => "Which best describes your company's role?",
                        'type' => 'select',
                        'description' => 'Company Role',
                        'options' => ['Program Manager', 'Sponsor Bank', 'Platform Provider', 'Direct MSB', 'Compliance Vendor', 'Fintech App', 'Other'],
                        'is_required' => true,
                    ],
                    [
                        'question' => 'Describe your role',
                        'type' => 'text',
                        'description' => 'Other Role Description',
                        'is_required' => true,
                    ],
                    [
                        'question' => 'What financial products or services do you offer?',
                        'type' => 'checkbox',
                        'description' => 'Financial Products & Services',
                        'options' => ['Checking/Deposit Accounts', 'Credit/Loans', 'BNPL', 'Debit/Prepaid Cards', 'International Remittances', 'Wire Transfers', 'ACH Payments', 'FX', 'Stablecoins', 'Crypto', 'Digital Wallets', 'Insurance', 'Securities', 'Other'],
                        'is_required' => true,
                    ],
                    [
                        'question' => 'Describe other products or services',
                        'type' => 'textarea',
                        'description' => 'Other Products Description',
                        'is_required' => true,
                    ],
                    [
                        'question' => 'How do customers access your services?',
                        'type' => 'checkbox',
                        'description' => 'Service Delivery Channels',
                        'options' => ['Mobile App', 'Website', 'Physical Branch', 'API/Embedded', 'Agent Network', 'ATM/Kiosk', 'Other'],
                        'is_required' => true,
                    ],
                    [
                        'question' => 'Describe other delivery channels',
                        'type' => 'textarea',
                        'description' => 'Other Delivery Channels',
                        'is_required' => true,
                    ],
                    [
                        'question' => 'What payment rails or funding methods are used?',
                        'type' => 'checkbox',
                        'description' => 'Payment Rails & Funding Methods',
                        'options' => ['ACH', 'Wire', 'Cash', 'Checks', 'Crypto', 'Debit Card', 'Stablecoin', 'Credit Card', 'FX', 'Other'],
                        'is_required' => true,
                    ],
                    [
                        'question' => 'Describe other payment methods',
                        'type' => 'textarea',
                        'description' => 'Other Payment Methods',
                        'is_required' => true,
                    ],
                    [
                        'question' => 'Do you rely on a licensed third party to offer financial services?',
                        'type' => 'select',
                        'description' => 'Third Party Dependencies',
                        'options' => ['Yes', 'No'],
                        'is_required' => true,
                    ],
                    [
                        'question' => 'Enter name and role of third party',
                        'type' => 'textarea',
                        'description' => 'Third Party Details',
                        'is_required' => true,
                    ],
                    [
                        'question' => 'Any additional details about your business model?',
                        'type' => 'textarea',
                        'description' => 'Additional Business Model Details',
                        'is_required' => false,
                    ],
                ]
            ],
            [
                'title' => 'Customer Onboarding & KYC',
                'section' => 'customer_onboarding',
                'description' => 'Details about customer identification, verification, and onboarding procedures',
                'questions' => [
                    [
                        'question' => 'Who are your typical customers?',
                        'type' => 'select',
                        'description' => 'Customer Types',
                        'options' => ['Consumers (individuals)', 'Businesses (entities)', 'Both'],
                        'is_required' => true,
                    ],
                    [
                        'question' => 'What consumer information do you collect and verify?',
                        'type' => 'checkbox',
                        'description' => 'Consumer Information Collection',
                        'options' => ['Full Name', 'Date of Birth', 'Address', 'SSN/TIN', 'Govt ID', 'Selfie/Photo', 'Other'],
                        'is_required' => true,
                    ],
                    [
                        'question' => 'How is consumer identity verified?',
                        'type' => 'select',
                        'description' => 'Consumer Identity Verification',
                        'options' => ['Manual', 'Vendor', 'Both'],
                        'is_required' => true,
                    ],
                    [
                        'question' => 'Consumer KYC vendor (if applicable)',
                        'type' => 'text',
                        'description' => 'Consumer KYC Vendor',
                        'is_required' => false,
                    ],
                    [
                        'question' => 'What business information do you collect and verify?',
                        'type' => 'checkbox',
                        'description' => 'Business Information Collection',
                        'options' => ['Business Name', 'Incorporation Docs', 'EIN/TIN', 'Address', 'UBO Names', 'UBO IDs', 'Control Person', 'Other'],
                        'is_required' => true,
                    ],
                    [
                        'question' => 'How is business identity verified?',
                        'type' => 'select',
                        'description' => 'Business Identity Verification',
                        'options' => ['Manual', 'Vendor', 'Both'],
                        'is_required' => true,
                    ],
                    [
                        'question' => 'Business KYC vendor (if applicable)',
                        'type' => 'text',
                        'description' => 'Business KYC Vendor',
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Where do your customers primarily reside or transact from?',
                        'type' => 'select',
                        'description' => 'Customer Primary Residence',
                        'options' => ['US', 'Canada', 'UK', 'Other'],
                        'is_required' => true,
                    ],
                ]
            ],

            [
                'title' => 'Customer Geography',
                'section' => 'customer_geography',
                'description' => 'Understand customer geographic distribution and high-risk jurisdictions',
                'questions' => [
                    [
                        'question' => 'Where are your customers located?',
                        'type' => 'checkbox',
                        'description' => 'Customer Locations',
                        'options' => ['US', 'Canada', 'Latin America', 'Africa', 'Middle East', 'Asia', 'Europe', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'List other countries or regions',
                        'type' => 'textarea',
                        'description' => 'Other Customer Locations',
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Do you serve or onboard any high-risk customer types or use cases?',
                        'type' => 'checkbox',
                        'description' => 'High-Risk Customer Types',
                        'options' => ['Crypto-related', 'Cross-border Payments', 'Charities/NGOs', 'Gambling', 'Cannabis', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Describe other high-risk categories',
                        'type' => 'textarea',
                        'description' => 'Other High-Risk Categories',
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Does your company have foreign operations, employees, or offices?',
                        'type' => 'select',
                        'description' => 'Foreign Operations',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Which countries?',
                        'type' => 'textarea',
                        'description' => 'Foreign Operations Countries',
                        'is_required' => false,
                    ],
                ]
            ],
            [
                'title' => 'Transaction Monitoring',
                'section' => 'transaction_monitoring',
                'description' => 'Details about your transaction monitoring and alert processes',
                'questions' => [
                    [
                        'question' => 'Do you have a transaction monitoring program?',
                        'type' => 'select',
                        'description' => 'Transaction Monitoring Program',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'What types of activity are monitored?',
                        'type' => 'checkbox',
                        'description' => 'Monitored Activity Types',
                        'options' => ['Structuring', 'High Velocity', 'Offshore Wires', 'Unusual Patterns', 'High-Risk Jurisdictions', 'High-Risk MCCs', 'ID Theft', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Is your monitoring system manual, automated, or both?',
                        'type' => 'select',
                        'description' => 'Monitoring System Type',
                        'options' => ['Manual', 'Automated', 'Hybrid'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Who reviews alerts and escalates red flags?',
                        'type' => 'select',
                        'description' => 'Alert Review Process',
                        'options' => ['Compliance Team', 'CCO', 'Operations', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Who do you report suspicious activity to (if applicable)?',
                        'type' => 'checkbox',
                        'description' => 'Suspicious Activity Reporting',
                        'options' => ['FinCEN', 'Sponsor Bank', 'Internal Only', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Do you file SARs or UARs?',
                        'type' => 'select',
                        'description' => 'SAR/UAR Filing',
                        'options' => ['Yes', 'No', 'Not Applicable'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Additional details about your monitoring or reporting?',
                        'type' => 'textarea',
                        'description' => 'Additional Monitoring Details',
                        'is_required' => false,
                    ],
                ]
            ],
            [
                'title' => 'OFAC & Sanctions Screening',
                'section' => 'ofac_sanctions',
                'description' => 'Sanctions screening and compliance procedures',
                'questions' => [
                    [
                        'question' => 'Do you have a sanctions screening process?',
                        'type' => 'select',
                        'description' => 'Sanctions Screening Process',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Who or what do you screen?',
                        'type' => 'checkbox',
                        'description' => 'Screening Scope',
                        'options' => ['Customers at onboarding', 'Customers ongoing', 'Transactions', 'Vendors', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Do you use a vendor for screening?',
                        'type' => 'select',
                        'description' => 'Screening Vendor Usage',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Screening vendor name',
                        'type' => 'text',
                        'description' => 'Screening Vendor Name',
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Which lists are used?',
                        'type' => 'checkbox',
                        'description' => 'Watchlists Used',
                        'options' => ['OFAC SDN', 'UN', 'EU', 'UK', 'PEP', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Describe other watchlists',
                        'type' => 'textarea',
                        'description' => 'Other Watchlists',
                        'is_required' => false,
                    ],
                    [
                        'question' => 'How frequently is screening performed?',
                        'type' => 'select',
                        'description' => 'Screening Frequency',
                        'options' => ['Real-Time', 'Daily', 'Weekly', 'Monthly', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'How are potential matches handled?',
                        'type' => 'checkbox',
                        'description' => 'Potential Match Handling',
                        'options' => ['Escalate Internally', 'Block/Reject', 'Report to OFAC', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'How are true matches handled?',
                        'type' => 'checkbox',
                        'description' => 'True Match Handling',
                        'options' => ['Report to Regulator', 'Block', 'Escalate to Bank', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Anything else you\'d like to share about your screening process?',
                        'type' => 'textarea',
                        'description' => 'Additional Screening Details',
                        'is_required' => false,
                    ],
                ]
            ],
            [
                'title' => 'Customer Due Diligence',
                'section' => 'customer_due_diligence',
                'description' => 'Enhanced due diligence and customer risk classification processes',
                'questions' => [
                    [
                        'question' => 'Do you risk rate or classify your customers?',
                        'type' => 'select',
                        'description' => 'Customer Risk Rating',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'How are customers classified or scored?',
                        'type' => 'checkbox',
                        'description' => 'Customer Risk Classification',
                        'options' => ['Risk Tiering', 'Manual Review', 'Rule-Based System', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Do you apply Enhanced Due Diligence (EDD) to any customers?',
                        'type' => 'select',
                        'description' => 'Enhanced Due Diligence Application',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'What triggers EDD?',
                        'type' => 'checkbox',
                        'description' => 'EDD Triggers',
                        'options' => ['High-Risk Geography', 'High-Risk Product', 'PEP', 'Adverse Media', 'Large Transactions', 'Internal Decision', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Describe other EDD triggers',
                        'type' => 'textarea',
                        'description' => 'Other EDD Triggers',
                        'is_required' => false,
                    ],
                    [
                        'question' => 'What steps are included in your EDD process?',
                        'type' => 'checkbox',
                        'description' => 'EDD Process Steps',
                        'options' => ['Additional Documents', 'Senior Review', 'Source of Funds', 'Screening', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Describe other EDD measures',
                        'type' => 'textarea',
                        'description' => 'Other EDD Measures',
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Do you conduct ongoing monitoring of high-risk customers?',
                        'type' => 'select',
                        'description' => 'Ongoing Monitoring',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Additional details about your due diligence process?',
                        'type' => 'textarea',
                        'description' => 'Additional Due Diligence Details',
                        'is_required' => false,
                    ],
                ]
            ],
            [
                'title' => 'Training & Governance',
                'section' => 'training_governance',
                'description' => 'BSA/AML training, policies, and governance structures',
                'questions' => [
                    [
                        'question' => 'Do you have a BSA/AML and OFAC training program?',
                        'type' => 'select',
                        'description' => 'BSA/AML and OFAC Training Program',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Who receives training?',
                        'type' => 'checkbox',
                        'description' => 'Training Audience',
                        'options' => ['Compliance Team', 'All Employees', 'Executives', 'Vendors'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'How often is training conducted?',
                        'type' => 'select',
                        'description' => 'Training Schedule',
                        'options' => ['At onboarding', 'Annually', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Is training effectiveness tested?',
                        'type' => 'select',
                        'description' => 'Training Effectiveness Testing',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Do you retain logs or evidence of training?',
                        'type' => 'select',
                        'description' => 'Training Record Retention',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Who owns and maintains your compliance policies?',
                        'type' => 'select',
                        'description' => 'Policy Ownership',
                        'options' => ['CCO', 'CEO', 'BOD', 'GC', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'If Other, please explain',
                        'type' => 'textarea',
                        'description' => 'Other Policy Owner',
                        'is_required' => false,
                    ],
                ]
            ],
            [
                'title' => 'Compliance Governance',
                'section' => 'compliance_governance',
                'description' => 'Chief Compliance Officer, board oversight, and independent testing',
                'questions' => [
                    [
                        'question' => 'Do you have a designated Chief Compliance Officer (CCO)?',
                        'type' => 'select',
                        'description' => 'Chief Compliance Officer',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'When was the CCO appointed?',
                        'type' => 'text',
                        'description' => 'CCO Appointment Date',
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Who does the CCO report to?',
                        'type' => 'select',
                        'description' => 'CCO Reporting Line',
                        'options' => ['CEO', 'GC', 'BOD', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'If Other, please explain',
                        'type' => 'textarea',
                        'description' => 'Other CCO Reporting Line',
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Do you have a board or governance committee overseeing compliance?',
                        'type' => 'select',
                        'description' => 'Board Governance Committee',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Do you conduct independent audits or testing of your compliance program?',
                        'type' => 'select',
                        'description' => 'Independent Testing',
                        'options' => ['Yes', 'Planned', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'How frequently is testing performed?',
                        'type' => 'select',
                        'description' => 'Independent Testing Frequency',
                        'options' => ['Annually', 'Every 2 Years', 'As Needed', 'Other'],
                        'is_required' => false,
                    ],
                ]
            ],
            [
                'title' => 'Vendor Oversight & Management',
                'section' => 'vendor_oversight',
                'description' => 'Vendor relationships and compliance oversight',
                'questions' => [
                    [
                        'question' => 'Do you rely on any vendors to support compliance (e.g., KYC, screening, monitoring)?',
                        'type' => 'select',
                        'description' => 'Compliance Vendor Usage',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'How do you evaluate or monitor compliance vendors?',
                        'type' => 'checkbox',
                        'description' => 'Vendor Evaluation Methods',
                        'options' => ['Policy Review', 'Security Due Diligence', 'Periodic Review', 'None'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Do you use a system or vendor to maintain compliance records?',
                        'type' => 'select',
                        'description' => 'Compliance Record System',
                        'options' => ['Yes', 'Manual', 'Other'],
                        'is_required' => false,
                    ],
                ]
            ],
            [
                'title' => 'Special Compliance Requirements',
                'section' => 'special_compliance',
                'description' => 'High-risk activities and special regulatory requirements',
                'questions' => [
                    [
                        'question' => 'Do you facilitate or send/receive transfers ≥ $3,000 (Travel Rule)?',
                        'type' => 'select',
                        'description' => 'Travel Rule Compliance',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Do you maintain any foreign correspondent accounts?',
                        'type' => 'select',
                        'description' => 'Foreign Correspondent Accounts',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Do you offer private banking services?',
                        'type' => 'select',
                        'description' => 'Private Banking Services',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Do you support or facilitate internet gambling or gaming?',
                        'type' => 'select',
                        'description' => 'Internet Gambling/Gaming',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Do you issue or sell monetary instruments?',
                        'type' => 'select',
                        'description' => 'Monetary Instruments',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Do you deal in or accept physical cash?',
                        'type' => 'select',
                        'description' => 'Physical Cash Handling',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                ]
            ],
            [
                'title' => 'Record Keeping & Retention',
                'section' => 'recordkeeping',
                'description' => 'Compliance record retention policies and procedures',
                'questions' => [
                    [
                        'question' => 'Do you have a process to retain compliance records (e.g., KYC, SARs, alerts)?',
                        'type' => 'select',
                        'description' => 'Compliance Record Retention Process',
                        'options' => ['Yes', 'No'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'What is your standard record retention period?',
                        'type' => 'select',
                        'description' => 'Record Retention Period',
                        'options' => ['5 years', '7 years', 'Other'],
                        'is_required' => false,
                    ],
                    [
                        'question' => 'Please specify your retention period',
                        'type' => 'textarea',
                        'description' => 'Other Retention Period',
                        'is_required' => false,
                    ],
                ]
            ],
        ];

        // Create questionnaires with their questions
        foreach ($questionnaires as $index => $questionnaireData) {
            $questions = $questionnaireData['questions'];
            unset($questionnaireData['questions']);
            $section = Section::firstOrCreate([
                'name' => $questionnaireData['section'],
            ]);
            // Create the questionnaire
            $questionnaire = Questionnaire::create([
                'title' => $questionnaireData['title'],
                'section_id' => $section->id,
                // 'section' => $questionnaireData['section'],
                'description' => $questionnaireData['description'],
                'allow_anonymous' => false,
                'allow_multiple_responses' => false,
                'show_progress' => true,
                'randomize_questions' => false,
                'status' => 'active',
                'user_id' => 1, // Default admin user ID
            ]);

            // Create questions for this questionnaire
            foreach ($questions as $questionIndex => $questionData) {
                Question::create([
                    'questionnaire_id' => $questionnaire->id,
                    'question' => $questionData['question'],
                    'type' => $questionData['type'],
                    'description' => $questionData['description'] ?? null,
                    'options' => isset($questionData['options']) ? json_encode($questionData['options']) : null,
                    'is_required' => $questionData['is_required'] ?? false,
                    'order' => $questionIndex + 1,
                ]);
            }
        }
    }
}
