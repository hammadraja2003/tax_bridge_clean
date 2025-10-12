@extends('layouts.login')
@section('content')
@section('title', 'Privacy Policy - Secureism (Private) Limited')

@section('content')
<div class="container py-4 privacypolicy">
    <h1 class="mb-4 text-center">Privacy Policy</h1>
    <p class="text-muted text-center">Last updated: {{ date('F d, Y') }}</p>
 
<div class="card p-5">
    <h4 class="mt-4">Introduction</h4>
    <p>
        This Privacy Statement applies to <strong>Secureism (Private) Limited</strong> (“Secureism,” “we,” “our,” or “us”)
        and our operations, including our website <a href="https://www.secureism.com" target="_blank">www.secureism.com</a>
        (“Website”) and all related professional and managed cybersecurity services (“Services”).
    </p>
    <p>
        At Secureism, we are committed to safeguarding the confidentiality, integrity, and availability of our clients’ 
        information while delivering trusted cybersecurity, compliance, and risk management solutions. By using our Website 
        or engaging with our Services, you consent to the collection, use, and disclosure of your personal and business 
        information as outlined in this Privacy Statement.
    </p>

    <h4 class="mt-4">Scope</h4>
    <p>
        This Privacy Statement explains how we collect, use, disclose, store, and safeguard personal and organizational 
        information in accordance with applicable data protection and cybersecurity laws, including the 
        <strong>Personal Data Protection Bill of Pakistan</strong> and international standards such as <strong>GDPR</strong> where relevant.
        This policy applies to all interactions with Secureism, including when you:
    </p>
    <ul>
        <li>Visit or use our Website,</li>
        <li>Subscribe to our Services,</li>
        <li>Engage with our consultants, or</li>
        <li>Communicate with us by email, phone, or other channels.</li>
    </ul>

    <h4 class="mt-4">Our Services</h4>
    <p>Secureism provides advanced cybersecurity solutions to protect businesses from evolving digital threats. Our key offerings include:</p>
    <ul>
        <li><strong>Managed Security Services (CYFEN – SOC-as-a-Service):</strong> 24/7 monitoring, detection, and incident response.</li>
        <li><strong>Security Assessment & Testing:</strong> Vulnerability assessments, penetration testing, and system hardening.</li>
        <li><strong>GRC Consulting:</strong> Compliance auditing, risk assessment, and policy advisory for regulatory adherence.</li>
        <li><strong>Source Code Security Review:</strong> Detailed analysis of software source code to identify and remediate security weaknesses.</li>
    </ul>

    <h4 class="mt-4">Information We Collect</h4>
    <ul>
        <li><strong>Business Information:</strong> Organization name, registration details, industry type, and contact data.</li>
        <li><strong>User Information:</strong> Name, designation, email address, phone number, and login credentials.</li>
        <li><strong>Technical Data:</strong> IP addresses, browser type, operating system, and access logs when you visit our Website or use our platforms.</li>
        <li><strong>Service Data:</strong> Information provided during security assessments, audits, or SOC operations (logs, alerts, configurations).</li>
        <li><strong>Financial Information:</strong> Billing address and payment details (processed securely via third-party providers).</li>
    </ul>
    <p>We may collect this data directly from you, through engagement processes, or from publicly available and lawful sources.</p>

    <h4 class="mt-4">How We Use Information</h4>
    <ul>
        <li>Deliver and manage our cybersecurity and compliance services.</li>
        <li>Conduct assessments, audits, and monitoring as per contractual scope.</li>
        <li>Maintain communication with clients, partners, and vendors.</li>
        <li>Improve our Website, systems, and service offerings.</li>
        <li>Fulfil legal and regulatory obligations related to cybersecurity or data protection.</li>
        <li>Provide technical and customer support.</li>
        <li>Conduct security analytics, threat intelligence, and service improvements.</li>
    </ul>
    <p>We will only use your information for the purposes stated above or those compatible with the original purpose of collection.</p>

    <h4 class="mt-4">Data Quality and Retention</h4>
    <p>We take reasonable steps to ensure that all collected data is accurate, complete, and up to date. You are encouraged to notify us of any inaccuracies or changes.</p>
    <p>We retain personal and business data only for as long as required to:</p>
    <ul>
        <li>Fulfil the purposes outlined in this policy,</li>
        <li>Comply with contractual or legal obligations, or</li>
        <li>Defend against potential disputes.</li>
    </ul>
    <p>Once data is no longer needed, it is securely deleted or anonymized.</p>

    <h4 class="mt-4">Security of Information</h4>
    <p>As a cybersecurity company, Secureism implements strict controls to protect all data we handle. Our security framework includes:</p>
    <ul>
        <li>End-to-end encryption for sensitive data transfers.</li>
        <li>Controlled access and multi-factor authentication for internal systems.</li>
        <li>Continuous monitoring of servers and networks.</li>
        <li>Periodic security audits and compliance reviews.</li>
    </ul>
    <p>
        While we maintain robust security measures, no system is completely immune from risk. Users share information at their discretion and understanding of inherent internet limitations.
    </p>

    <h4 class="mt-4">Disclosure of Information</h4>
    <p>We may disclose your information under the following circumstances:</p>
    <ul>
        <li><strong>Service Providers:</strong> Third parties providing hosting, analytics, or payment processing, all bound by confidentiality agreements.</li>
        <li><strong>Regulatory or Law Enforcement:</strong> Where disclosure is legally required or requested by authorities.</li>
        <li><strong>Professional Advisors:</strong> Legal, accounting, or compliance experts engaged to support operations.</li>
        <li><strong>Business Transfers:</strong> In case of mergers, acquisitions, or corporate restructuring ensuring service continuity.</li>
    </ul>
    <p><strong>We do not sell or rent personal information</strong> to any third party.</p>

    <h4 class="mt-4">International Transfers</h4>
    <p>
        Your information may be processed or stored in data centers outside your home country (e.g., EU or other regions). 
        We ensure adequate protection measures such as <strong>Standard Contractual Clauses</strong> or other recognized mechanisms.
    </p>

    <h4 class="mt-4">Third-Party Websites and Integrations</h4>
    <p>
        Our Website or reports may include links to external tools, platforms, or resources. Secureism does not control or 
        endorse their privacy practices. We encourage users to review third-party privacy policies before sharing information.
    </p>

    <h4 class="mt-4">Your Rights</h4>
    <p>You have the right to:</p>
    <ul>
        <li>Request access to personal information we hold about you.</li>
        <li>Request correction or deletion of inaccurate data.</li>
        <li>Withdraw consent where applicable.</li>
        <li>Request details on how your information is processed.</li>
    </ul>
    <p>
        To exercise these rights, please contact our Privacy Officer (details below). Verification of identity may be required.
    </p>

    <h4 class="mt-4">Cookies and Analytics</h4>
    <p>
        Our Website may use cookies and analytics tools to enhance your experience and understand visitor interactions.
        Cookies help us personalize content, improve functionality, and analyze traffic. You can disable cookies through
        browser settings, though some features may not function correctly.
    </p>

    <h4 class="mt-4">Updates to This Privacy Policy</h4>
    <p>
        We may update this Privacy Policy periodically to reflect legal or operational changes. The latest version will
        always be available at <a href="https://www.secureism.com" target="_blank">www.secureism.com</a>. Significant 
        updates may also be communicated via email or service notifications.
    </p>

    <h4 class="mt-4">Contact Information</h4>
    <p>
        If you have any questions, requests, or complaints regarding this Privacy Policy or our data-handling practices, please contact:
    </p>
    <ul class="list-unstyled">
        <li>📧 <a href="mailto:info@secureism.com">info@secureism.com</a></li>
        <li>🌐 <a href="https://www.secureism.com" target="_blank">www.secureism.com</a></li>
    </ul>
</div>
</div>
 
@endsection
