<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login11.html');
    exit();
}
include "db.php";
$email = $_SESSION['email'];
$registered_events = [];
$stmt = $conn->prepare("SELECT events FROM eventregistrations WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $events = explode(", ", $row['events']);
    $registered_events = array_merge($registered_events, $events);
}
$registered_events = array_unique($registered_events);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>NSS Events Week — Rule Book</title>
    <style>
        :root {
            --bg1: #0f172a;
            --accent: #4cc9f0;
            --card: #0b1220;
            --muted: #9aa7c7;
            --gold: #ffe45c;
            --glass: rgba(255, 255, 255, 0.04);
            --radius: 18px;
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, #0b1220 0%, #1b2330 50%, #0f172a 100%);
            color: #e6eef8;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            min-height: 100vh;
            padding: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wrap {
            width: 100%;
            max-width: 980px;
        }

        header {
            display: flex;
            gap: 16px;
            align-items: center;
            margin-bottom: 18px;
        }

        .logo {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), #7b2cbf);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #041022;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5), inset 0 -6px 18px rgba(255, 255, 255, 0.02);
            flex-shrink: 0;
        }

        header h1 {
            font-size: 20px;
            margin: 0
        }

        header p {
            margin: 0;
            color: var(--muted);
            font-size: 13px
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 20px;
        }

        /* Left: content */
        .card {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.02), rgba(255, 255, 255, 0.01));
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: 0 10px 30px rgba(2, 6, 23, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.03);
        }

        nav {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .tab {
            padding: 8px 12px;
            background: var(--glass);
            border-radius: 999px;
            color: var(--muted);
            font-weight: 600;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.02);
            transition: all .18s ease;
            font-size: 13px;
        }

        .tab.active {
            background: linear-gradient(90deg, var(--accent), #7b2cbf);
            color: #041022;
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(76, 201, 240, 0.12);
        }

        .section {
            display: none;
            padding-top: 6px;
            line-height: 1.55;
        }

        .section.active {
            display: block
        }

        .section h2 {
            margin: 4px 0 8px;
            font-size: 18px;
            color: var(--accent)
        }

        .rules {
            background: rgba(255, 255, 255, 0.02);
            padding: 12px;
            border-radius: 12px;
            border: 1px dashed rgba(255, 255, 255, 0.02)
        }

        .rules ol {
            padding-left: 18px;
            margin: 0;
        }

        .rules li {
            margin: 8px 0;
            font-size: 14px;
            color: #eaf6ff
        }

        .subtitle {
            font-weight: 700;
            color: var(--gold);
            margin-top: 12px;
            margin-bottom: 8px
        }

        .btns {
            display: flex;
            gap: 10px;
            margin-top: 12px
        }

        .btn {
            padding: 10px 14px;
            border-radius: 12px;
            background: linear-gradient(90deg, var(--accent), #7b2cbf);
            color: #041022;
            text-decoration: none;
            font-weight: 700;
            border: none;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 10px 24px rgba(11, 18, 32, 0.6);
        }

        .btn.ghost {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.06);
            color: var(--muted);
            font-weight: 600
        }

        /* Right: quick links + print instructions */
        aside .info {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.02), rgba(255, 255, 255, 0.01));
            padding: 18px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.03);
        }

        aside h3 {
            margin: 0 0 8px;
            color: var(--accent)
        }

        aside p {
            margin: 0;
            color: var(--muted);
            font-size: 13px
        }

        .links {
            margin-top: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px
        }

        .small-link {
            background: rgba(255, 255, 255, 0.02);
            padding: 8px;
            border-radius: 10px;
            font-size: 13px;
            color: var(--muted);
            display: flex;
            justify-content: space-between;
            align-items: center
        }

        .small-link a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 700
        }

        footer {
            margin-top: 16px;
            color: var(--muted);
            font-size: 13px;
            text-align: center
        }

        .register button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(90deg, var(--accent), #7b2cbf);
            color: #041022;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(11, 18, 32, 0.6);
        }

       .register button:hover {
            background: linear-gradient(90deg, #7b2cbf, var(--accent));
        }

        /* PRINT: only print the chosen section when printing via the Print buttons */
        @media print {
            body * {
                visibility: hidden
            }

            .print-target,
            .print-target * {
                visibility: visible
            }

            .print-target {
                position: fixed;
                left: 0;
                top: 0;
                width: 100%
            }
        }

        /* Responsive */
        @media (max-width:900px) {
            .grid {
                grid-template-columns: 1fr;
                padding-bottom: 30px
            }

            aside {
                order: 2
            }

            header {
                flex-direction: row;
                gap: 12px
            }
        }
    </style>
</head>

<body>
    <nav style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: rgba(255,255,255,0.1); border-radius: 10px; margin-bottom: 20px;">
        <div style="color: #e6eef8;">Registered Events: <?php echo implode(", ", $registered_events); ?></div>
        <a href="logout.php" style="display: inline-block; width: 30px; height: 30px; background: #ff4757; color: white; border-radius: 50%; text-align: center; line-height: 30px; text-decoration: none; font-weight: bold; font-size: 14px;">L</a>
    </nav>
    <div class="wrap">
        <header>
            <div class="logo">NSS</div>
            <div>
                <h1>NSS Events Week — Rule Book</h1>
                <p>Tap a day, read the rules, or print/save each day as PDF.</p>
            </div>
        </header>

        <div class="grid">
            <!-- Left: main content -->
            <div class="card">
                <nav id="tabs">
                    <div class="tab active" data-target="day1">Day 1</div>
                    <div class="tab" data-target="day2">Day 2</div>
                    <div class="tab" data-target="day3">Day 3</div>
                    <div class="tab" data-target="day4">Day 4</div>
                </nav>

                <!-- Day 1 -->
                <section id="day1" class="section active">
                    <h2>Day 1 — Pick & Speak / Painting</h2>

                    <div class="subtitle">Pick and Speak</div>
                    <div class="rules">
                        <ol>
                            <li><strong>Theme:</strong> Social Awareness / Environment / Youth Power.</li>
                            <li>Each participant picks one topic randomly from the bowl.</li>
                            <li>Preparation time – <strong>1 minute</strong>. Speaking time – <strong>2
                                    minutes</strong>.</li>
                            <li>No mobile phones or written notes allowed on stage.</li>
                            <li>Judging based on Confidence, Clarity, Relevance, Body Language.</li>
                            <li>Judge decisions are final.</li>
                        </ol>
                    </div>

                    <div class="subtitle">Painting</div>
                    <div class="rules">
                        <ol>
                            <li><strong>Theme:</strong> "Clean India, Green India".</li>
                            <li>Duration – <strong>1 hour</strong>. Only A3 sheets provided by organizers.</li>
                            <li>Participants bring their own paints, brushes, palettes (no sharing required).</li>
                            <li>No pre-drawn sketches, tracing, or digital prints allowed.</li>
                            <li>Judgment: Creativity, Neatness, Message Clarity.</li>
                        </ol>
                    </div>

                    <div class="btns">
                        <button class="btn" onclick="printSection('day1')">Print / Save Day 1 (PDF)</button>
                        <button class="btn ghost" onclick="shareLink('#day1')">Copy Day 1 Link</button>
                    </div>
                </section>

                <!-- Day 2 -->
                <section id="day2" class="section">
                    <h2>Day 2 — Debate / Essay Writing</h2>

                    <div class="subtitle">Debate</div>
                    <div class="rules">
                        <ol>
                            <li>Topic announced on the spot.</li>
                            <li>Team of <strong>2 members</strong> (one for, one against).</li>
                            <li>Each speaker: <strong>3 minutes</strong>. Rebuttal: <strong>1 minute</strong>.</li>
                            <li>No abusive or disrespectful language. Respect is mandatory.</li>
                            <li>Marks: Content, Delivery, Counterpoints. Judges' decision is final.</li>
                        </ol>
                    </div>

                    <div class="subtitle">Essay Writing</div>
                    <div class="rules">
                        <ol>
                            <li><strong>Theme:</strong> Role of Youth in Nation Building.</li>
                            <li>Word limit: <strong>500–600 words</strong>. Duration: <strong>45 minutes</strong>.</li>
                            <li>Write neatly in your own handwriting. Plagiarism => disqualification.</li>
                        </ol>
                    </div>

                    <div class="btns">
                        <button class="btn" onclick="printSection('day2')">Print / Save Day 2 (PDF)</button>
                        <button class="btn ghost" onclick="shareLink('#day2')">Copy Day 2 Link</button>
                    </div>
                </section>

                <!-- Day 3 -->
                <section id="day3" class="section">
                    <h2>Day 3 — Poster Making / Cleanliness Drive</h2>

                    <div class="subtitle">Poster Making</div>
                    <div class="rules">
                        <ol>
                            <li><strong>Theme:</strong> Save Nature, Save Future.</li>
                            <li>Individual participation only. Time limit – <strong>1 hour</strong>.</li>
                            <li>Bring your own materials. Judgment: Creativity, Message, Design.</li>
                        </ol>
                    </div>

                    <div class="subtitle">Cleanliness Drive</div>
                    <div class="rules">
                        <ol>
                            <li>Team participation (max <strong>5 members</strong> per team).</li>
                            <li>Carry reusable gloves, masks, and dustbins. Safety first.</li>
                            <li>Focus areas: campus garden, nearby streets, community spots.</li>
                            <li>Promote awareness; certificates for Best Team Initiative.</li>
                        </ol>
                    </div>

                    <div class="btns">
                        <button class="btn" onclick="printSection('day3')">Print / Save Day 3 (PDF)</button>
                        <button class="btn ghost" onclick="shareLink('#day3')">Copy Day 3 Link</button>
                    </div>
                </section>

                <!-- Day 4 -->
                <section id="day4" class="section">
                    <h2>Day 4 — Yoga Session</h2>

                    <div class="subtitle">Yoga Competition / Session</div>
                    <div class="rules">
                        <ol>
                            <li>Wear proper yoga attire. Bring your own yoga mat.</li>
                            <li>Perform <strong>5 compulsory asanas</strong> + <strong>1 optional asana</strong>.</li>
                            <li>Time limit – <strong>5 minutes</strong> per participant.</li>
                            <li>Judging: Flexibility, Posture, Balance, Presentation.</li>
                        </ol>
                    </div>

                    <div class="btns">
                        <button class="btn" onclick="printSection('day4')">Print / Save Day 4 (PDF)</button>
                        <button class="btn ghost" onclick="shareLink('#day4')">Copy Day 4 Link</button>
                    </div>
                </section>

                <footer>
                    <small>Organizers reserve the right to change schedules & rules. Be respectful, be safe.</small>
                </footer>
            </div>

            <!-- Right: quick info -->
            <aside>
                <div class="info card">
                    <h3>Quick tips</h3>
                    <p>Use the "Print / Save" button on each day to save that day's rules as PDF (Print → Save as PDF).
                    </p>

                    <div class="links">
                        <div class="small-link">
                            <span>Share overall link</span>
                            <a href="#" onclick="copyToClipboard(window.location.href);return false;">Copy</a>
                        </div>
                    </div>

                    <hr
                        style="border:none;height:1px;background:rgba(255,255,255,0.02);margin:12px 0;border-radius:2px">
                </div>
            </aside>
            <div class="register">
                <form action="registration1.php"><button>Register for Events</button></form>
            </div>
            
            
        </div>
    </div>

    <script>
        // Tab switching
        const tabs = document.querySelectorAll('.tab');
        const sections = document.querySelectorAll('.section');

        tabs.forEach(t => {
            t.addEventListener('click', () => {
                tabs.forEach(x => x.classList.remove('active'));
                t.classList.add('active');

                const target = t.dataset.target;
                sections.forEach(s => {
                    if (s.id === target) s.classList.add('active');
                    else s.classList.remove('active');
                });
                // Update URL hash for sharing
                history.replaceState(null, '', `#${target}`);
            });
        });

        // Print only the selected section
        function printSection(sectionId) {
            const target = document.getElementById(sectionId);
            if (!target) return;
            // add print-target class to target, remove from others
            document.querySelectorAll('.section').forEach(s => s.classList.remove('print-target'));
            target.classList.add('print-target');

            // wait a tick then print
            setTimeout(() => {
                window.print();
                // cleanup
                target.classList.remove('print-target');
            }, 150);
        }

        // share link for specific section (copies current page with hash)
        function shareLink(hash) {
            const url = window.location.origin + window.location.pathname + hash;
            copyToClipboard(url);
            showToast('Link copied to clipboard!');
        }

        function copyToClipboard(text) {
            navigator.clipboard?.writeText(text).catch(() => { });
        }

        // toast (tiny)
        function showToast(msg) {
            const el = document.createElement('div');
            el.textContent = msg;
            Object.assign(el.style, {
                position: 'fixed', left: '50%', transform: 'translateX(-50%)', bottom: '28px', background: 'rgba(0,0,0,0.6)',
                color: '#fff', padding: '10px 14px', borderRadius: '10px', fontSize: '13px', zIndex: 9999
            });
            document.body.appendChild(el);
            setTimeout(() => el.style.opacity = '0', 2500);
            setTimeout(() => el.remove(), 3000);
        }

        // On load: open the tab that matches hash (if any)
        window.addEventListener('load', () => {
            const h = location.hash.replace('#', '');
            if (h) {
                const tab = document.querySelector(`.tab[data-target="${h}"]`);
                if (tab) tab.click();
            }
        });
    </script>
</body>

</html>